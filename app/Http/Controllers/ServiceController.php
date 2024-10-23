<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(){

        // Obtener los servicios con la información de la empresa y colaborador
        $servicios = Service::join('service_collaborators', 'services.id', '=', 'service_collaborators.service_id')
            ->join('user_enterprises', 'service_collaborators.user_enterprise_id', '=', 'user_enterprises.id')
            ->join('enterprises', 'user_enterprises.enterprise_id', '=', 'enterprises.id')
            ->join('users', 'user_enterprises.user_id', '=', 'users.id')
            ->select(
                'services.id AS service_id',
                'services.name AS service_name',
                'enterprises.name AS enterprise_name',
                'services.description AS description', 
                'services.price AS price',
                'services.duration AS duration'
            )
            ->get();

        return view('servicios.indexServicios', compact('servicios'));
    }
    
        
    public function guardar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
        ]);
    
        // Accede a la empresa asociada al usuario
        $empresa = auth()->user()->enterprises->first(); // Obtener la primera empresa
    
        if (!$empresa) {
            return redirect()->back()->with('error', 'No se encontró una empresa asociada al usuario.');
        }
    
        $servicio = new Service();
        $servicio->name = $request->name;
        $servicio->description = $request->description;
        $servicio->price = $request->price;
        $servicio->duration = $request->duration;
    
        // Asegúrate de asignar el ID de la empresa correctamente
        $servicio->empresa_id = $empresa->id; // Cambia 'enterprise_id' a 'empresa_id' si es el nombre correcto en la base de datos
    
        $servicio->save();
    
        return redirect()->back()->with('success', 'Servicio creado exitosamente.');
    }
    
    
}
