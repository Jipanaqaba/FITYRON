<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class EncuestaController extends Controller
{
   
    public function show()
    {
          // Si el usuario está autenticado, redirigir según su estado
    if (Auth::check()) {
        return Auth::user()->informacion_completa 
            ? redirect()->route('home') 
            : view('encuesta');
    }

    // Si es invitado, mostrar encuesta con datos de sesión (si existen)
    return view('encuesta', [
        'datosTemporales' => session('encuesta_guest')
    ]);
    }

    public function storeGuest(Request $request) {
        // Validar datos del invitado
        $request->validate([
            'nombre' => 'required|string',
            'edad' => 'required|integer',
            'genero' => 'required|in:masculino,femenino',
            'objetivo' => 'required|in:perder grasa,ganar músculo,mantenerme en forma',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'experiencia' => 'required|in:principiante,intermedio,avanzado'
        ]);
    
        // Guardar en sesión (funcionará con database driver)
        session()->put('encuesta_guest', $request->except('_token'));
    
        return redirect()->route('register')->with('info', 'Regístrate para guardar tu progreso');
    }


    // Guardar los datos de la encuesta
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'edad' => 'required|integer',
            'genero' => 'required|in:masculino,femenino',
            'objetivo' => 'required|in:perder grasa,ganar músculo,mantenerme en forma',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'experiencia' => 'required|in:principiante,intermedio,avanzado',
        ]);
    
      
    /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update([
                'nombre' => $request->nombre,
                'edad' => $request->edad,
                'genero' => $request->genero,
                'objetivo' => $request->objetivo,
                'peso' => $request->peso,
                'altura' => $request->altura,
                'experiencia' => $request->experiencia,
                'informacion_completa' => true
            ]);
    
        return redirect()->route('home');
    }
}
