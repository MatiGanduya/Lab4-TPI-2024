<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Location;
use App\Models\User_enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('empresa.indexEmpresa');
    }

    public function guardar(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postalCode' => 'required|string|max:10',
        ]);

        // Crear la ubicación de la empresa
        $location = new Location();
        $location->country = $request->country;
        $location->province = $request->state;
        $location->city = $request->city;
        $location->address = $request->direccion;
        $location->postal_code = $request->postalCode;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;

        $location->save();

        // Crear la empresa con la ubicación creada
        $enterprise = new Enterprise();
        $enterprise->name = $request->nombre;
        $enterprise->location_id = $location->id; // Relacionar con la ubicación creada
        $enterprise->save();

        // Crear la relación en la tabla user_enterprises
        User_enterprise::create([
            'user_id' => Auth::id(),  // Usuario autenticado
            'enterprise_id' => $enterprise->id,
            'user_type' => 'admin', // Puedes ajustar esto según lo necesites
        ]);

        // Redirigir o devolver una respuesta exitosa
        return redirect()->back()->with('success', 'Empresa guardada correctamente.');
    }
}
