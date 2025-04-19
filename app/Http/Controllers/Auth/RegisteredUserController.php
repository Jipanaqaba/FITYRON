<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Crear usuario con datos básicos
        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'informacion_completa' => false, // Por defecto, no ha completado la encuesta
        ]);
    
        // Si hay datos de encuesta como invitado, actualizar y marcar como completo
        if(session()->has('encuesta_guest')) {
            $datosGuest = session('encuesta_guest');
            $user->update([
                'edad' => $datosGuest['edad'],
                'genero' => $datosGuest['genero'],
                'objetivo' => $datosGuest['objetivo'],
                'peso' => $datosGuest['peso'],
                'altura' => $datosGuest['altura'],
                'experiencia' => $datosGuest['experiencia'],
                'informacion_completa' => true // Marcar como completo
            ]);
            session()->forget('encuesta_guest');
        }
    
        event(new Registered($user));
        Auth::login($user);
    
        // Redirigir según si completó la encuesta
        return $user->informacion_completa 
            ? redirect()->route('home') // Si tenía datos de invitado
            : redirect()->route('encuesta'); // Si es registro normal
    }
}
