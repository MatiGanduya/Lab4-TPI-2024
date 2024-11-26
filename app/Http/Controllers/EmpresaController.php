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
        $usuario = Auth::user();

        if (!$usuario instanceof User || $usuario->user_type === 'employee') {
            return redirect()->back()->with('error', 'No tiene permisos para guardar o editar empresas.');
        }
        $request->validate([
            'id' => 'nullable|exists:enterprises,id',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500', 
            'descripcion' => 'nullable|string|max:500',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postalCode' => 'required|string|max:10',
        ]);

        if (!$usuario instanceof User) {
            return redirect()->back()->with('error', 'Usuario no autenticado o inválido.');
        }
        $enterprise = Enterprise::find($request->input('id'));
    
        if ($enterprise) {
            $enterprise->name = $request->input('nombre');
            $enterprise->description = $request->input('descripcion'); // Guardar descripción
            $enterprise->save();
    
            if ($enterprise->location) {
                $enterprise->location->address = $request->input('direccion');
                $enterprise->location->country = $request->input('country');
                $enterprise->location->province = $request->input('state');
                $enterprise->location->city = $request->input('city');
                $enterprise->location->postal_code = $request->input('postalCode');
                $enterprise->location->latitude = $request->input('latitude');
                $enterprise->location->longitude = $request->input('longitude');
                $enterprise->location->save();
            }
        } else {
            $location = new Location();
            $location->country = $request->country;
            $location->province = $request->state;
            $location->city = $request->city;
            $location->address = $request->direccion;
            $location->postal_code = $request->postalCode;
            $location->latitude = $request->latitude;
            $location->longitude = $request->longitude;
            $location->save();
    
            $enterprise = new Enterprise();
            $enterprise->name = $request->nombre;
            $enterprise->description = $request->descripcion; 
            $enterprise->description = $request->descripcion;
            $enterprise->location_id = $location->id;
            $enterprise->owner_id = Auth::id();
            $enterprise->save();
        }

        User_enterprise::updateOrCreate(
            ['user_id' => Auth::id(), 'enterprise_id' => $enterprise->id],
            ['user_type' => 'admin']
        );


        $usuario->user_type = 'admin';

    
        if ($usuario->user_type !== null) {
            $usuario->save(); 
            $usuario->save();
        } else {
            return redirect()->back()->with('error', 'El tipo de usuario no puede ser nulo.');
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
    
        // Obtener colaboradores relacionados con la empresa
        $colaboradores = $empresa->users()->whereNotNull('name')->get();
    
        return view('ruta_de_tu_vista', [
            'empresa' => $empresa,
            'colaboradores' => $colaboradores,
        ]);
    }
    
    
}

