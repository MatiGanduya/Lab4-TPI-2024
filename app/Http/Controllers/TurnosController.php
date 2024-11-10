<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Availability;
use App\Models\User_enterprise;
use Carbon\Carbon;


class TurnosController extends Controller
{
    public function index(){
        return view('turnos.indexTurnos');
    }

    public function seleccionFechaHora($servicio_id)
    {
        // Obtener el servicio basado en el ID
        $servicio = Service::findOrFail($servicio_id);

        // Aquí podrías pasar cualquier otro dato necesario para la vista, como disponibilidad de turnos, etc.
        return view('turnos.seleccionFechayHora', compact('servicio'));
    }

    public function mostrarDisponibilidad($servicio_id, $fecha)
{
    try {
        // Obtener el servicio por su ID
        $servicio = Service::findOrFail($servicio_id);

        // Obtener la empresa asociada al servicio
        $empresa = $servicio->enterprise;

        // Obtener el usuario que pertenece a esta empresa
        $usuario = $empresa->userEnterprises->first()->user;

        // Convertir la fecha en formato Y-m-d para comparar con las disponibilidades
        $fecha = Carbon::parse($fecha);
        $dia_de_la_semana = $fecha->dayOfWeek; // Obtener el día de la semana (0 para domingo, 6 para sábado)

        // Obtener la disponibilidad del usuario para el día seleccionado
        $disponibilidad = Availability::where('userProf_id', $usuario->id)
            ->where('day_of_week', $dia_de_la_semana)
            ->get();

        // Preparar las horas disponibles
        $horasDisponibles = [];
        foreach ($disponibilidad as $disponible) {
            $startTime = strtotime($disponible->start_time);
            $endTime = strtotime($disponible->end_time);

            while ($startTime < $endTime) {
                $horasDisponibles[] = date('H:i', $startTime);
                $startTime += 30 * 60; // Incrementar en intervalos de 30 minutos
            }
        }

        // Asegurarse de que se retorna una respuesta JSON correctamente
        return response()->json($horasDisponibles);
    } catch (\Exception $e) {
        // En caso de error, capturamos el error y lo logueamos
        return response()->json(['error' => 'Hubo un problema al procesar la solicitud.'], 500);
    }
}


    public function confirmar(Request $request, $servicio_id)
    {
        // Obtener el servicio por su ID
        $servicio = Service::findOrFail($servicio_id);

        // Aquí puedes procesar la lógica de confirmación del turno
        // Acceder a los datos del formulario
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');

        // Ejemplo de lógica de confirmación
        // Podrías, por ejemplo, guardar un nuevo turno en la base de datos

        // Mostrar un mensaje o redirigir según sea necesario
        return redirect()->route('turnos.confirmado')->with('success', 'Turno confirmado');
    }

    // public function seleccionFechayHora($servicio_id)
    // {
    //     // Recuperamos el servicio con su ID
    //     $servicio = Service::findOrFail($servicio_id);

    //     // Obtenemos el ID del profesional asociado al servicio
    //     $userProf_id = $servicio->userProf_id;

    //     // Obtenemos el día de la semana en español
    //     $dayOfWeek = now()->locale('es')->format('l');  // "Lunes", "Martes", etc.

    //     // Realizamos la consulta para obtener la disponibilidad del profesional en el día de la semana actual
    //     $disponibilidad = Availability::where('userProf_id', $userProf_id)
    //                                     ->where('day_of_week', $dayOfWeek)
    //                                     ->get();

    //     // Generamos bloques de media hora a partir de las horas de inicio y fin
    //     $bloques = [];
    //     foreach ($disponibilidad as $d) {
    //         $start = \Carbon\Carbon::parse($d->start_time);
    //         $end = \Carbon\Carbon::parse($d->end_time);

    //         while ($start < $end) {
    //             $bloques[] = [
    //                 'start_time' => $start->format('H:i'),
    //                 'end_time' => $start->addMinutes(30)->format('H:i')
    //             ];
    //         }
    //     }

    //     // Devolvemos la vista con los datos
    //     return view('turnos.seleccionFechayHora', compact('servicio', 'bloques'));
    // }


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
