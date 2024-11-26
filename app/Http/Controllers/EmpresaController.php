<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Location;
use App\Models\User_enterprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->enterprises->first(); // Obtén la primera empresa asociada al usuario
        $clientes = User::where('user_type', 'client')->get(); // Usuarios tipo cliente
    
        // Retorna ambas variables a la vista
        return view('empresa.indexEmpresa', compact('empresa', 'clientes'));
    }

    public function guardar(Request $request)
    {
        $usuario = Auth::user();
    
        // Verificar si el usuario tiene permisos para guardar/editar empresas
        if (!$usuario || $usuario->user_type !== 'admin') {
            return redirect()->back()->with('error', 'No tiene permisos para guardar o editar empresas.');
        }
    
        $request->validate([
            'id' => 'nullable|exists:enterprises,id',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postalCode' => 'required|string|max:10',
        ]);
    
        $enterprise = Enterprise::find($request->input('id'));
    
        if ($enterprise) {
            // Actualizar empresa existente
            $enterprise->name = $request->input('nombre');
            $enterprise->description = $request->input('descripcion');
            $enterprise->save();
    
            // Actualizar ubicación
            if ($enterprise->location) {
                $enterprise->location->update([
                    'address' => $request->input('direccion'),
                    'country' => $request->input('country'),
                    'province' => $request->input('state'),
                    'city' => $request->input('city'),
                    'postal_code' => $request->input('postalCode'),
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                ]);
            }
        } else {
            // Crear una nueva empresa
            $location = Location::create([
                'country' => $request->input('country'),
                'province' => $request->input('state'),
                'city' => $request->input('city'),
                'address' => $request->input('direccion'),
                'postal_code' => $request->input('postalCode'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);
    
            $enterprise = Enterprise::create([
                'name' => $request->input('nombre'),
                'description' => $request->input('descripcion'),
                'location_id' => $location->id,
                'owner_id' => $usuario->id,
            ]);
        }
    
        return redirect()->back()->with('success', 'Empresa guardada correctamente.');
    }

    public function mostrarEmpresa()
    {
        $empresa = Enterprise::first();
        return view('empresa.indexEmpresa', compact('empresa'));
    }
    
    public function agregarColaborador(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'enterprise_id' => 'required|exists:enterprises,id',
        ]);
    
        // Verifica si ya existe la relación entre usuario y empresa
        if (User_enterprise::where('user_id', $request->user_id)
            ->where('enterprise_id', $request->enterprise_id)
            ->exists()) {
            return redirect()->back()->with('error', 'El usuario ya está vinculado a esta empresa.');
        }
    
        // Cambia el tipo de usuario a 'employee'
        $user = User::find($request->user_id);
        $user->user_type = 'employee';
        $user->save();
    
        // Crea la relación entre usuario y empresa
        User_enterprise::create([
            'user_id' => $request->user_id,
            'enterprise_id' => $request->enterprise_id,
            'user_type' => 'employee',
        ]);
    
        return redirect()->back()->with('success', 'Colaborador agregado exitosamente.');
    }

    public function gestionarColaboradores($enterpriseId)
    {
        $empresa = Enterprise::findOrFail($enterpriseId);
        
        // Obtener todos los colaboradores (empleados) asociados a la empresa
        $colaboradores = $empresa->users()->where('user_enterprise.user_type', 'employee')->get();
    
        return view('ruta_de_tu_vista', [
            'empresa' => $empresa,
            'colaboradores' => $colaboradores,
        ]);
    }
    
}

