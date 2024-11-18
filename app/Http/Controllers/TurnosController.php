<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Availability;
use App\Models\User_enterprise;
use App\Models\Appointment;
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

            $duracionCarbon = Carbon::parse($servicio->duration);
            $duracionServicios = $duracionCarbon->hour * 60 + $duracionCarbon->minute; // Total en minutos
                 // Asegúrate de usar la variable correcta
            //dd($duracionServicios);
            // Obtener los turnos ocupados en la misma fecha
            $turnosOcupados = Appointment::whereDate('appointment_date', $fecha)
                ->get(['appointment_date']); // Solo selecciona la columna appointment_date


            // Convertir los turnos ocupados en un formato que sea fácil de comparar
            $horariosOcupados = [];
            foreach ($turnosOcupados as $turno) {
                $startTime = Carbon::parse($turno->appointment_date); // Inicio del turno
                $endTime = $startTime->copy()->addMinutes($duracionServicios); // Fin del turno


                //dd($startTime);
                //dd($endTime);
                // Agregar los intervalos ocupados
                while ($startTime < $endTime) {
                    $horariosOcupados[] = $startTime->format('H:i');
                    $startTime->addMinutes(30); // Los turnos ocupados también se verifican en intervalos de 30 minutos
                }
            }
            //dd($horariosOcupados);

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

                    $startTime->addMinutes(30); // Incrementar en intervalos de 30 minutos
                }
            }
            //dd($servicio, $empresa, $usuario);

            // Asegurarse de que se retorna una respuesta JSON correctamente
            return response()->json($horasDisponibles);
        } catch (\Exception $e) {
            // En caso de error, capturamos el error y lo logueamos
            return response()->json(['error' => $e->getMessage()], 500); // Incluimos el mensaje de error real para depurar mejor
        }
    }


}
