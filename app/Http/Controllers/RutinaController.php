<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RutinaEjercicio;
use App\Services\GeneradorRutinaService;
use App\Models\Ejercicio;
use App\Models\User;
use App\Models\SesionEntrenamiento;

class RutinaController extends Controller
{
    public function agregarEjercicio(Request $request, $rutinaId) {
        $request->validate([
            'ejercicio_id' => 'required|exists:ejercicios,id',
            'series' => 'required|integer|min:1',
            'repeticiones' => 'required|integer|min:1',
            'dia' => 'required|in:L,M,X,J,V,S,D',
        ]);

        $rutina = RutinaEjercicio::findOrFail($rutinaId);
        $rutina->ejercicios()->attach($request->ejercicio_id, [
            'series' => $request->series,
            'repeticiones' => $request->repeticiones,
            'dia' => $request->dia
        ]);

        return response()->json(['message' => 'Ejercicio agregado a rutina']);
    }


public function generar(Request $request, GeneradorRutinaService $generadorRutina)
{
    $user = Auth::user();

      if (empty($user->dias_entrenamiento)) {
        return redirect()->route('encuesta')->with('error', 'Primero completa tus días de entrenamiento');
    }
    $dias = $user->dias_entrenamiento ?? [];

    if (empty($dias)) {
        return redirect()->route('encuesta')->with('error', 'Primero completa tus días de entrenamiento');
    }

    $generadorRutina->generarRutinaPara($user); 
    return redirect()->route('home')->with('success', 'Rutina generada correctamente');
}

public function mostrarDia($dia)
{
    $usuario = Auth::user();
    $rutinas = $usuario->rutinas()->latest()->first(); 

    if (!$rutinas) {
        return redirect()->route('home')->with('error', 'No tienes rutina.');
    }

    $ejercicios = $rutinas->ejercicios()->wherePivot('dia', $dia)->get();  

     $diasSemana = ['L' => 'lunes', 'M' => 'martes', 'X' => 'miércoles', 'J' => 'jueves', 'V' => 'viernes', 'S' => 'sábado', 'D' => 'domingo'];
    $nombreDia = $diasSemana[$dia] ?? $dia;

    return view('rutina_dia', compact('ejercicios', 'nombreDia'));
}


// En tu controlador RutinaController
public function ejecutarDia($dia)
{
    $usuario = Auth::user();
    $rutina = $usuario->rutinas()->latest()->first();

    // Validar existencia de rutina
    if (!$rutina) return redirect()->route('home')->with('error', 'No existe la rutina');

    // Obtener ejercicios con validación de URLs
    $ejercicios = $rutina->ejercicios()
        ->wherePivot('dia', $dia)
        ->get()
        ->map(function($ejercicio) {
            $gifUrl = filter_var($ejercicio->gifUrl, FILTER_VALIDATE_URL) 
                    ? $ejercicio->gifUrl 
                    : asset('storage/' . $ejercicio->gifUrl);

            return [
                'nombre' => $ejercicio->nombre,
                'gifUrl' => $gifUrl,
                'series' => (int)$ejercicio->pivot->series,
                'duracion_serie' => 60,
                'descanso' => 120
            ];
        });

    return view('rutina', [
        'ejercicios' => $ejercicios->shuffle(), // Mezclar ejercicios
        'nombreDia' => ucfirst($dia)
    ]);
}

public function finalizar(Request $request)
{
    $usuario = Auth::user();
    $rutina = $usuario->rutinas()->latest()->first();

    if (!$rutina) {
        return redirect()->route('home')->with('error', 'No hay rutina activa para finalizar.');
    }

    // Detectar automáticamente el día actual (ej: L, M, X, etc.)
     $mapaDias = ['lunes' => 'L', 'martes' => 'M', 'miércoles' => 'X', 'jueves' => 'J', 'viernes' => 'V', 'sábado' => 'S', 'domingo' => 'D'];
$diaNombre = strtolower(now()->locale('es')->isoFormat('dddd'));
$diaLetra = $mapaDias[$diaNombre] ?? 'D';


    // Contar cuántos ejercicios realmente hizo el usuario
    $ejerciciosDelDia = $rutina->ejercicios()->wherePivot('dia', $diaLetra)->count();

    SesionEntrenamiento::create([
        'usuario_id' => $usuario->id,
        'rutina_id' => $rutina->id,
        'fecha' => now()->toDateString(),
        'dia' => $diaLetra,
        'completado' => $ejerciciosDelDia >= 8 // Consideramos "completa" si hizo 8 o más
    ]);

    return redirect()->route('home')->with('success', 'Sesión registrada correctamente');
}


}