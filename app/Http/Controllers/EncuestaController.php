<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class EncuestaController extends Controller
{
   
    public function show()
    {
        // Si el usuario está autenticado, guardamos los datos en la sesión
        if (Auth::check()) {
            session(['encuesta_incompleta' => true]);
        }

        return view('encuesta');
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
            'password' => 'required|min:8', 
            'email' => 'required|email|unique:usuarios'
        ]);
    
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'edad' => $request->edad,
                'genero' => $request->genero,
                'objetivo' => $request->objetivo,
                'peso' => $request->peso,
                'altura' => $request->altura,
                'experiencia' => $request->experiencia,
                'informacion_completa' => true
            ]);
    
            session()->forget('encuesta_incompleta');
        }
    
        return redirect()->route('home');
    }
}
