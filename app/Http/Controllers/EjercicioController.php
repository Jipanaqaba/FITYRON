<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ejercicio;

class EjercicioController extends Controller
{
    public function index() {
        return Ejercicio::with(['parteCuerpo', 'musculoObjetivo', 'equipamiento'])->get();
    }

    public function show($id) {
        return Ejercicio::with(['parteCuerpo', 'musculoObjetivo', 'equipamiento'])->findOrFail($id);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'dificultad' => 'required|in:principiante,intermedio,avanzado',
            'parte_cuerpo_id' => 'required|exists:partes_cuerpo,id',
             'secondary_muscles' => 'nullable|array', 
            'secondary_muscles.*' => 'string',        
            'instructions' => 'nullable|array',      
            'instructions.*' => 'string',  
        ]);
        
        return Ejercicio::create([
            'nombre' => $validated['nombre'],
            'dificultad' => $validated['dificultad'],
            'parte_cuerpo_id' => $validated['parte_cuerpo_id'],
            'secondary_muscles' => isset($validated['secondary_muscles']) ? json_encode($validated['secondary_muscles']) : null,  
            'instructions' => isset($validated['instructions']) ? json_encode($validated['instructions']) : null,  
        ]);
    }
}