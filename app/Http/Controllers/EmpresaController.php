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
        $user = Auth::user();
        $empresa = $user->enterprises->first();
        return view('empresa.indexEmpresa', compact('empresa'));
    }

    public function guardar(Request $request)
    {
        
        $request->validate([
            'id' => 'nullable|exists:enterprises,id', 
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postalCode' => 'required|string|max:10',
        ]);

        $enterprise = Enterprise::find($request->input('id'));

        if ($enterprise) {
    
            $enterprise->name = $request->input('nombre');
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
            $enterprise->location_id = $location->id;
            $enterprise->owner_id = Auth::id();
            $enterprise->save();
        }

        
        User_enterprise::updateOrCreate( 
            ['user_id' => Auth::id(), 'enterprise_id' => $enterprise->id],
            ['user_type' => 'admin'] 
        );

        
        return redirect()->back()->with('success', 'Empresa guardada correctamente.');
    }

    public function mostrarEmpresa()
    {
        $empresa = Enterprise::first(); 
        return view('empresa.indexEmpresa', compact('empresa')); 
  }
}

