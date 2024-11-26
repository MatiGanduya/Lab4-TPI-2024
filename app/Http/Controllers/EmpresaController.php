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
        $empresa = $user->enterprises->first();
        $clientes = User::where('user_type', 'client')->get(); // Usuarios tipo cliente
        return view('empresa.indexEmpresa', compact('empresa', 'clientes'));

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

    public function agregarColaborador(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'enterprise_id' => 'required|exists:enterprises,id',
        ]);

        // Actualizar el tipo de usuario a "employee"
        $user = User::find($request->user_id);
        $user->user_type = 'employee'; // Enum: 'client', 'employee', etc.
        $user->save();

        // Crear la relación en "user_enterprises"
        User_enterprise::create([
            'user_id' => $request->user_id,
            'enterprise_id' => $request->enterprise_id,
            'user_type' => 'employee', // Enum
        ]);
        if (User_enterprise::where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'El usuario ya está vinculado a una empresa'], 400);
        }

        return response()->json(['message' => 'Colaborador agregado exitosamente']);
    }

    public function getUsuariosPorEmpresa($empresa_id)
    {
        try {
            // Depurar el ID de la empresa
            \Log::info("Empresa ID recibido: {$empresa_id}");

            // Buscar usuarios relacionados con la empresa
            $usuarios = User::whereHas('enterprises', function ($query) use ($empresa_id) {
                $query->where('enterprises.id', $empresa_id);
            })->get();

            // Depurar los usuarios encontrados
            \Log::info("Usuarios encontrados: ", $usuarios->toArray());

            // Si no se encuentran usuarios, devolver un mensaje apropiado
            if ($usuarios->isEmpty()) {
                return response()->json(['message' => 'No se encontraron usuarios para esta empresa.'], 404);
            }

            // Devolver los usuarios como JSON
            return response()->json($usuarios);

        } catch (\Exception $e) {
            // Capturar errores y mostrar el mensaje
            \Log::error("Error al obtener usuarios: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()], 500);
        }
    }

}

