<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiciosController extends Controller
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
}
