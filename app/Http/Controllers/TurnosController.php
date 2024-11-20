<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Availability;
use App\Models\User_enterprise;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class TurnosController extends Controller
{
    public function index()
    {
        // Obtener los turnos del usuario autenticado (esto es un ejemplo)
        $turnos = Appointment::with(['service', 'service.enterprise'])
                            ->where('user_id', Auth::id()) // Asegúrate de que Auth esté configurado correctamente
                            ->get()
                            ->map(function ($turno) {
                                // Formatear la fecha si es necesario
                                $turno->formatted_date = Carbon::parse($turno->appointment_date)->format('d/m/Y H:i');
                                return $turno;
                            });

        // Pasar los turnos a la vista
        return view('turnos.index', compact('turnos'));
    }


    public function seleccionFechaHora($servicio_id, $usuario_colaborador_id)
    {
        // Obtener el servicio basado en el ID
        $servicio = Service::findOrFail($servicio_id);

        // Obtener el colaborador basado en el ID
        $colaborador = User::findOrFail($usuario_colaborador_id);
        //dd($colaborador);
        // Aquí puedes pasar otros datos necesarios, como la disponibilidad del colaborador o detalles adicionales
        return view('turnos.seleccionFechayHora', compact('servicio', 'colaborador'));
    }


    public function mostrarDisponibilidad($servicio_id, $usuario_colaborador_id, $fecha)
{
    try {
        // Obtener el servicio por su ID
        $servicio = Service::findOrFail($servicio_id);

        // Obtener el colaborador basado en el ID
        $colaborador = User::findOrFail($usuario_colaborador_id);

        // Convertir la fecha en formato Y-m-d para comparar con las disponibilidades
        $fecha = Carbon::parse($fecha);
        $dia_de_la_semana = $fecha->dayOfWeek; // Obtener el día de la semana (0 para domingo, 6 para sábado)

        // Obtener la disponibilidad del colaborador para el día seleccionado
        $disponibilidad = Availability::where('userProf_id', $colaborador->id)
            ->where('day_of_week', $dia_de_la_semana)
            ->get();

        $duracionCarbon = Carbon::parse($servicio->duration);
        $duracionServicios = $duracionCarbon->hour * 60 + $duracionCarbon->minute; // Total en minutos

        // Obtener los turnos ocupados en la misma fecha
        $turnosOcupados = Appointment::whereDate('appointment_date', $fecha)
            ->get(['appointment_date']); // Solo selecciona la columna appointment_date

        // Convertir los turnos ocupados en un formato que sea fácil de comparar
        $horariosOcupados = [];
        foreach ($turnosOcupados as $turno) {
            $startTime = Carbon::parse($turno->appointment_date); // Inicio del turno
            $endTime = $startTime->copy()->addMinutes($duracionServicios); // Fin del turno

            // Agregar los intervalos ocupados
            while ($startTime < $endTime) {
                $horariosOcupados[] = $startTime->format('H:i');
                $startTime->addMinutes(15); // Los turnos ocupados también se verifican en intervalos de 15 minutos
            }
        }

        // Preparar las horas disponibles
        $horasDisponibles = [];
        foreach ($disponibilidad as $disponible) {
            $startTime = Carbon::parse($disponible->start_time);
            $endTime = Carbon::parse($disponible->end_time);

            while ($startTime < $endTime) {
                $hora = $startTime->format('H:i');

                // Si la hora no está ocupada, la agregamos a las horas disponibles
                if (!in_array($hora, $horariosOcupados)) {
                    $horasDisponibles[] = $hora;
                }

                $startTime->addMinutes(15); // Incrementar en intervalos de 15 minutos
            }
        }

        // Asegurarse de que se retorna una respuesta JSON correctamente
        return response()->json($horasDisponibles);
    } catch (\Exception $e) {
        // En caso de error, capturamos el error y lo logueamos
        return response()->json(['error' => $e->getMessage()], 500); // Incluimos el mensaje de error real para depurar mejor
    }
}



}
