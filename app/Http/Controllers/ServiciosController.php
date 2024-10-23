<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index(){


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
}
