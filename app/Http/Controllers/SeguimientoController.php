<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seguimiento;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $usuarioId =  $usuario->id; // Obtener ID del usuario autenticado

        $historial = Seguimiento::where('usuario_id', $usuarioId)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        $necesitaSeguimiento = $this->necesitaSeguimiento($usuarioId);

        return view('seguimiento', compact('historial', 'necesitaSeguimiento'));
    }

   public function registrar(Request $request)
{
    $validated = $request->validate([
        'peso' => 'required|numeric',
        'medidas.pecho' => 'required|numeric',
        'medidas.cintura' => 'required|numeric',
        'medidas.brazos' => 'required|numeric',
        'medidas.cuello' => 'required|numeric',
        'medidas.muslo' => 'required|numeric',
        'medidas.pantorrilla' => 'required|numeric',
        'usuario_id' => 'required|exists:usuarios,id',
    ]);

    $validated['fecha_registro'] = now();
    Seguimiento::create($validated);

    return redirect()->route('seguimiento')->with('success', 'Seguimiento registrado correctamente.');
}

    public function necesitaSeguimiento($usuarioId)
    {
        $ultimo = Seguimiento::where('usuario_id', $usuarioId)
            ->orderBy('fecha_registro', 'desc')
            ->first();

        if (!$ultimo) return true;

        return $ultimo->fecha_registro->lt(now()->startOfMonth());
    }
}
