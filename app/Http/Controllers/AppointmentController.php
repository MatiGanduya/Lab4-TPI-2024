<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{
    public function index()
    {
        $turnos = Appointment::with(['service', 'service.enterprise'])
                    ->where('user_id', Auth::id())
                    ->get()
                    ->map(function ($turno) {
                        $turno->formatted_date = Carbon::parse($turno->appointment_date)->format('d/m/Y H:i');
                        return $turno;
                    });

        return view('turnos.index', compact('turnos'));
    }


    public function store(Request $request, $servicio_id)
    {
        // Validar los datos
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        // Combinar la fecha y la hora en un solo campo de fecha y hora
        $appointmentDate = Carbon::parse("{$request->fecha} {$request->hora}");

        // Obtener el servicio por su ID
        $servicio = Service::findOrFail($servicio_id);

        // Obtener la empresa asociada al servicio
        $empresa = $servicio->enterprise;

        $usuarioProfesional = $empresa->userEnterprises->first()->user;

        Appointment::create([
            'appointment_date' => $appointmentDate,
            'status' => 'pending',
            'user_id' => auth()->id(),
            'userProf_id' => $usuarioProfesional->id,
            'service_id' => $servicio_id,
        ]);

        // Redirigir o retornar una respuesta
        return redirect()->route('turnos.indexTurnos')->with('success', 'Turno confirmado exitosamente');
    }

    public function cancel($id)
    {
        $turno = Appointment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $turno->status = 'cancelled';
        $turno->save();

        return redirect()->route('turnos.indexTurnos')->with('success', 'Turno cancelado exitosamente');
    }

    public function getSolicitudes()
    {
        // Obtener todas las solicitudes relacionadas al usuario autenticado
        $solicitudes = Appointment::with(['service', 'service.enterprise'])
                        ->where('userProf_id', Auth::id()) // Filtrar solo las solicitudes del profesional autenticado
                        ->get()
                        ->map(function ($solicitud) {
                            $solicitud->formatted_date = Carbon::parse($solicitud->appointment_date)->format('d/m/Y H:i');
                            return $solicitud;
                        });

        // Retornar todas las solicitudes a la vista
        return view('solicitudes.index', compact('solicitudes'));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        // Obtener la solicitud por ID y asegurar que pertenece al usuario autenticado
        $solicitud = Appointment::where('id', $id)
                        ->where('userProf_id', Auth::id())
                        ->firstOrFail();

        // Actualizar el estado
        $solicitud->status = $request->status;
        $solicitud->save();

        return redirect()->route('solicitudes.turnos')->with('success', 'Estado de la solicitud actualizado correctamente');
    }

    public function checkPendingRequests()
    {
        $hasPendingRequests = Appointment::where('status', 'pending')
            ->where('userProf_id', Auth::id()) // Filtra solicitudes del profesional autenticado
            ->exists();

        // Retorna la vista con esta información
        return view('layouts.app', compact('hasPendingRequests'));
    }


}
