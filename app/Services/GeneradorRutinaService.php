<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ejercicio;
use App\Models\MusculoObjetivo;
use Illuminate\Support\Collection;

class GeneradorRutinaService
{
    public function generarRutinaPara(User $user)
    {
        // Obtener días de entrenamiento en formato correcto (lunes, martes, etc.)
        $dias = $user->dias_entrenamiento ?? [];

        // Crear la rutina principal
        $rutina = $user->rutinas()->create([
            'nombre' => 'Rutina '.ucfirst($user->objetivo),
            'nivel' => $user->experiencia,
            'objetivo' => $user->objetivo
        ]);

        // Obtener músculos objetivo según el objetivo del usuario
        $musculos = MusculoObjetivo::whereHas('ejercicios', function($query) use ($user) {
            $query->where('objetivo', $user->objetivo)
                  ->where('dificultad', $user->experiencia);
        })->get()->pluck('nombre_api');

        // Distribuir músculos entre los días seleccionados
        $musculosPorDia = $this->distribuirMusculos($musculos, count($dias));

        foreach ($dias as $index => $dia) {
            if (isset($musculosPorDia[$index])) {
                $this->agregarEjerciciosDia($rutina, $dia, $musculosPorDia[$index], $user);
            }
        }

        return $rutina;
    }

    private function distribuirMusculos(Collection $musculos, int $numDias): array
    {
        $distribucion = array_fill(0, $numDias, []);
        $i = 0;
        
        foreach ($musculos as $musculo) {
            $distribucion[$i % $numDias][] = $musculo;
            $i++;
        }
        
        return $distribucion;
    }

private function agregarEjerciciosDia($rutina, string $dia, array $musculos, User $user)
{
    $ejerciciosAgregados = collect();
    $maxEjerciciosPorDia = 8;

    foreach ($musculos as $musculo) {
        // Salimos si ya llegamos a los 8
        if ($ejerciciosAgregados->count() >= $maxEjerciciosPorDia) break;

        $restantes = $maxEjerciciosPorDia - $ejerciciosAgregados->count();

        $ejercicios = Ejercicio::where('objetivo', $user->objetivo)
            ->where('dificultad', $user->experiencia)
            ->whereHas('musculoObjetivo', function ($query) use ($musculo) {
                $query->where('nombre_api', $musculo);
            })
            ->whereNotIn('id', $ejerciciosAgregados) // evitar repetir
            ->inRandomOrder()
            ->limit(min(3, $restantes)) // máximo 3 por músculo, pero no más de los restantes
            ->get();

        foreach ($ejercicios as $ejercicio) {
            $rutina->ejercicios()->attach($ejercicio->id, [
                'series' => rand(3, 4),
                'repeticiones' => rand(8, 12),
                'dia' => strtolower($dia)
            ]);
            $ejerciciosAgregados->push($ejercicio->id);
        }
    }

    // Rellenar si aún no se llega a 8
    $faltan = $maxEjerciciosPorDia - $ejerciciosAgregados->count();

    if ($faltan > 0) {
        $extras = Ejercicio::where('objetivo', $user->objetivo)
            ->where('dificultad', $user->experiencia)
            ->whereNotIn('id', $ejerciciosAgregados)
            ->inRandomOrder()
            ->limit($faltan)
            ->get();

        foreach ($extras as $ejercicio) {
            $rutina->ejercicios()->attach($ejercicio->id, [
                'series' => rand(3, 4),
                'repeticiones' => rand(8, 12),
                'dia' => strtolower($dia)
            ]);
            $ejerciciosAgregados->push($ejercicio->id);
        }
    }
}


}