<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        // Obtener todos los servicios con la información de la empresa
        $servicios = Service::join('enterprises', 'services.empresa_id', '=', 'enterprises.id')
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
    
        
        $empresa = auth()->user()->enterprises->first(); 
    
        if (!$empresa) {
            return redirect()->back()->with('error', 'No se encontró una empresa asociada al usuario.');
        }
    
        $servicio = new Service();
        $servicio->name = $request->name;
        $servicio->description = $request->description;
        $servicio->price = $request->price;
        $servicio->duration = $request->duration;
    
        
        $servicio->empresa_id = $empresa->id;
        $servicio->save();
    
        return redirect()->back()->with('success', 'Servicio creado exitosamente.');
    }
    
    
}
