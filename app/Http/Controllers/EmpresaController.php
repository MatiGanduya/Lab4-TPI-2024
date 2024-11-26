<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Location;
use App\Models\User_enterprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmpresaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->enterprises->first(); // Asegúrate de que solo haya una empresa asociada al usuario

        // Obtener los colaboradores (usuarios con user_type 'employee') de la empresa
        $collaborators = $empresa->users()
                                 ->where('user_enterprises.user_type', 'employee') // Especificar la columna de la tabla pivote
                                 ->get();

        $clientes = User::where('user_type', 'client')->get(); // Obtener usuarios tipo cliente

        // Pasar las variables a la vista
        return view('empresa.indexEmpresa', compact('empresa', 'clientes', 'collaborators'));
    }



    public function guardar(Request $request)
    {

        $usuario = Auth::user();

        if (!$usuario instanceof User || $usuario->user_type === 'employee') {
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


    public function addCollaborator(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'enterprise_id' => 'required|exists:enterprises,id',
        ]);
    
        $enterprise = Enterprise::find($validated['enterprise_id']);
        $user = User::find($validated['user_id']);
    
        if (!$enterprise || !$user) {
            return response()->json(['success' => false, 'message' => 'Empresa o usuario no encontrado.']);
        }
    
        // Evitar duplicados
        if ($enterprise->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Este usuario ya es colaborador de la empresa.']);
        }
    
        // Agregar colaborador a la empresa
        $enterprise->users()->attach($user->id, ['user_type' => 'collaborator']);
    
        return response()->json(['success' => true, 'message' => 'Colaborador agregado exitosamente.']);
    }
    
    
    // Muestra los colaboradores de una empresa
    public function getCollaborators($enterpriseId)
    {
        $collaborators = DB::table('users_enterprise')
            ->join('users', 'users.id', '=', 'users_enterprise.user_id')
            ->where('users_enterprise.enterprise_id', $enterpriseId)
            ->where('users_enterprise.user_type', 'collaborator')
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return view('manage_collaborators', compact('collaborators', 'enterpriseId'));
    }

    public function removeCollaborator(Request $request)
    {
        $enterpriseId = $request->input('enterprise_id');
        $userId = $request->input('user_id');

        $relation = DB::table('user_enterprises')
            ->where('user_id', $userId)
            ->where('enterprise_id', $enterpriseId)
            ->where('user_type', 'employee')
            ->first();

        if ($relation) {
            // Eliminar la relación en la tabla user_enterprises
            DB::table('user_enterprises')
                ->where('user_id', $userId)
                ->where('enterprise_id', $enterpriseId)
                ->delete();

            // Cambiar el tipo de usuario a cliente
            $user = User::find($userId);
            $user->user_type = 'client';
            $user->save();

            // Redirigir con un mensaje de éxito
            return redirect()->back()->with('success', 'Colaborador eliminado correctamente.');
        }

        return redirect()->back()->with('error', 'El colaborador no se encontró o no pertenece a esta empresa.');
    }


}

