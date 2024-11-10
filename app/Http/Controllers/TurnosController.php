<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Availability;

class TurnosController extends Controller
{
    public function index(){
        return view('turnos.indexTurnos');
    }

    public function seleccionFechayHora($servicio_id)
    {
        // Recuperamos el servicio con su ID
        $servicio = Service::findOrFail($servicio_id);

        // Obtenemos el ID del profesional asociado al servicio
        $userProf_id = $servicio->userProf_id;

        // Obtenemos el día de la semana en español
        $dayOfWeek = now()->locale('es')->format('l');  // "Lunes", "Martes", etc.

        // Realizamos la consulta para obtener la disponibilidad del profesional en el día de la semana actual
        $disponibilidad = Availability::where('userProf_id', $userProf_id)
                                        ->where('day_of_week', $dayOfWeek)
                                        ->get();

        // Generamos bloques de media hora a partir de las horas de inicio y fin
        $bloques = [];
        foreach ($disponibilidad as $d) {
            $start = \Carbon\Carbon::parse($d->start_time);
            $end = \Carbon\Carbon::parse($d->end_time);

            while ($start < $end) {
                $bloques[] = [
                    'start_time' => $start->format('H:i'),
                    'end_time' => $start->addMinutes(30)->format('H:i')
                ];
            }
        }

        // Devolvemos la vista con los datos
        return view('turnos.seleccionFechayHora', compact('servicio', 'bloques'));
    }


    public function reservar(Request $request, $servicio_id)
    {
        // Valida los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
        ]);

        // Lógica para almacenar el turno, por ejemplo:
        // Turno::create([...]);

        return redirect()->route('turnos.index')->with('success', 'Turno reservado exitosamente');
    }
}
