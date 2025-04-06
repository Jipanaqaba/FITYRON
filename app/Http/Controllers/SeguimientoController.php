<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seguimiento;

class SeguimientoController extends Controller
{
    public function registrar(Request $request) {
        $validated = $request->validate([
            'peso' => 'required|numeric',
            'medidas' => 'required|json',
            'usuario_id' => 'required|exists:usuarios,id'
        ]);

        return Seguimiento::create($validated);
    }

    public function historial($usuarioId) {
        return Seguimiento::where('usuario_id', $usuarioId)
            ->orderBy('fecha_registro', 'desc')
            ->get();
    }
}
