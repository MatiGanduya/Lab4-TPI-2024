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
}
