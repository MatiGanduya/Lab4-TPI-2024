<?php

namespace App\Http\Controllers;
use App\Models\Availability;
use Illuminate\Http\Request;

class DisponibilidadController extends Controller
{
        // Mostrar disponibilidad de un usuario específico
        public function index()
        {
            $disponibilidades = Availability::where('userProf_id', auth()->id())->get();
            $user = auth()->user(); // Obtiene el usuario autenticado
            return view('disponibilidad.indexDisponibilidad', compact('disponibilidades', 'user'));
        }


        public function create()
        {
            return view('disponibilidad.create');
        }

        // Crear disponibilidad para un usuario (función adicional que ya habíamos definido)
        public function store(Request $request)
        {
            // Validación inicial de los datos
            $request->validate([
                'day_of_week' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);

            $userId = auth()->id();
            $dayOfWeek = $request->day_of_week;
            $startTime = $request->start_time;
            $endTime = $request->end_time;

            // Verificar superposición de horarios
            $conflict = Availability::where('userProf_id', $userId)
                ->where('day_of_week', $dayOfWeek)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime])
                          ->orWhere(function ($q) use ($startTime, $endTime) {
                              $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                          });
                })
                ->exists();

            if ($conflict) {
                return redirect()->back()->withErrors(['error' => 'El horario ingresado ya está siendo ocupado.']);
            }

            // Crear la nueva disponibilidad si no hay conflicto
            Availability::create([
                'userProf_id' => $userId,
                'day_of_week' => $dayOfWeek,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return redirect()->route('disponibilidad.indexDisponibilidad')->with('success', 'Disponibilidad creada exitosamente.');
        }

        // Agregar este método a DisponibilidadController
        public function horarios($dia)
        {
            // Obtener las disponibilidades para el día seleccionado
            $disponibilidades = Availability::where('day_of_week', $dia)
                ->whereIn('userProf_id', auth()->user()->enterprises->first()->users->pluck('id'))
                ->get(['start_time', 'end_time']);

            return response()->json(['horas' => $disponibilidades]);
        }

        public function destroy($id)
        {
            $disponibilidad = Availability::findOrFail($id);
            $disponibilidad->delete();

            return redirect()->route('disponibilidad.indexDisponibilidad')->with('success', 'Disponibilidad eliminada correctamente');
        }
}
