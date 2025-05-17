<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SesionEntrenamiento;
use App\Services\GeneradorRutinaService;

class HomeController extends Controller
{
  public function index()
    {
        $user = Auth::user();
        
        // Obtener todas las rutinas con sus ejercicios
        $rutinas = $user->rutinas()
        ->with(['ejercicios' => function($query) {
            $query->withPivot('dia', 'series', 'repeticiones');
        }])
        ->latest()
        ->get();

        // Preparar datos para el gráfico
        $labels = [];
        $datos = []; 
        //$datos = [8,6,8,5,8,4,8];
        for ($i = 6; $i >= 0; $i--) {
            $dia = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($dia)->locale('es')->isoFormat('dddd');
            
            $sesiones_completadas = SesionEntrenamiento::where('usuario_id', $user->id)
                ->whereDate('fecha', $dia)
                ->count();
            
            $datos[] = min($sesiones_completadas, 6); // Límite máximo de 6 sesiones
        }

       return view('home', compact('rutinas', 'user', 'labels', 'datos'));
    }

    public function generar(Request $request)
    {
        $user = Auth::user();
        
        try {
            app(GeneradorRutinaService::class)->generarRutinaPara($user);
            return redirect()->route('home')->with('success', '¡Rutina generada con éxito!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar rutina: ' . $e->getMessage());
        }
    }


}
