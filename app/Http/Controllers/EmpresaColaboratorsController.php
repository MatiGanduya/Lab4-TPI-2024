<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User_enterprise;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmpresaColaboratorsController extends Controller
{
    public function showColaboradores()
    {
        $user = Auth::user();
        $empresa = $user->enterprises->first(); // Obtén la empresa asociada al usuario actual
        $clientes = User::where('user_type', 'client')->get(); // Obtener usuarios tipo cliente
        $collaborators = $empresa->users()
        ->where('user_enterprises.user_type', 'employee') // Especificar la columna de la tabla pivote
        ->get(); // Obtén los colaboradores de la empresa

        return view('empresa.empresaColaboradores', compact('empresa', 'clientes', 'collaborators'));
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

    public function addCollaborator(Request $request)
    {
        // Validar la entrada del formulario
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validar el ID del usuario
            'enterprise_id' => 'required|exists:enterprises,id', // Validar el ID de la empresa
        ]);

        // Buscar al usuario por su ID
        $user = User::find($request->user_id);

        // Verificar si el usuario ya está vinculado a alguna empresa
        if (User_enterprise::where('user_id', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'El usuario ya está vinculado a una empresa.'], 400);
        }

        // Actualizar el tipo de usuario a "employee"
        $user->user_type = 'employee'; // Enum: 'client', 'employee', etc.
        $user->save();

        // Crear la relación en "users_enterprise"
        $relation = User_enterprise::create([
            'user_id' => $user->id,
            'enterprise_id' => $request->enterprise_id,
            'user_type' => 'employee', // Enum
        ]);

        // Respuesta JSON para solicitudes AJAX
        return redirect()->back()->with('success', 'Colaborador agregado con éxito.');
    }

    public function deleteCollaborator(Request $request)
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
