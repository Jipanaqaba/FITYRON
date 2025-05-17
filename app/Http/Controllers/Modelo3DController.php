<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo3D;
use App\Models\Ejercicio;
use App\Models\ParteCuerpo;
use App\Models\MusculoObjetivo;
use Illuminate\Support\Facades\Storage;

class Modelo3DController extends Controller
{

    public function index()
    {
        return view('modelos3d');
    }

public function getEjercicios($parteCuerpo)
{
    try {
        $parteCuerpo = str_replace('-', ' ', $parteCuerpo);
        $parte = ParteCuerpo::where('nombre_api', $parteCuerpo)->firstOrFail();

        $ejercicios = Ejercicio::where('parte_cuerpo_id', $parte->id)
            ->get()
            ->map(function ($ejercicio) {
                return [
                    'nombre' => $ejercicio->nombre,
                    'gif' => asset(Storage::url($ejercicio->gifUrl)),
                    'instructions' => $ejercicio->instructions
                ];
            });

        return response()->json($ejercicios);
        
    } catch (\Exception $e) {
        return response()->json(['error' => 'Recurso no encontrado'], 404);
    }
}

public function getEjerciciosPorMusculo($musculoApi)
{
    $musculo = MusculoObjetivo::where('nombre_api', $musculoApi)
        ->firstOrFail();

    $ejercicios = Ejercicio::with(['equipo', 'parteCuerpo'])
        ->where('musculo_objetivo_id', $musculo->id)
        ->get()
        ->map(function($ejercicio) {
            return [
                'nombre' => $ejercicio->nombre,
                'descripcion' => $ejercicio->descripcion,
                'dificultad' => $ejercicio->dificultad,
                'gifUrl' =>asset(Storage::url($ejercicio->gifUrl)),
                'equipoImg' => asset("storage/{$ejercicio->equipo->icono_url}"),
                'equipoNombre' => $ejercicio->equipo->nombre_mostrar
            ];
        });

    return response()->json([
        'musculoNombre' => $musculo->nombre_mostrar,
        'ejercicios' => $ejercicios
    ]);
}

}