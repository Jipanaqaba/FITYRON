<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RutinaEjercicio;

class RutinaController extends Controller
{
    public function agregarEjercicio(Request $request, $rutinaId) {
        $request->validate([
            'ejercicio_id' => 'required|exists:ejercicios,id',
            'series' => 'required|integer|min:1',
            'repeticiones' => 'required|integer|min:1'
        ]);

        $rutina = RutinaEjercicio::findOrFail($rutinaId);
        $rutina->ejercicios()->attach($request->ejercicio_id, [
            'series' => $request->series,
            'repeticiones' => $request->repeticiones
        ]);

        return response()->json(['message' => 'Ejercicio agregado a rutina']);
    }
}
