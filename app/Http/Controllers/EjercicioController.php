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
           
        ]);
        
        return Ejercicio::create($validated);
    }
}
