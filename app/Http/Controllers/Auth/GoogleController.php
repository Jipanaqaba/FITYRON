<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
  
            $googleUser = Socialite::driver('google')->user();
            // Buscar usuario por google_id PRIMERO (evitar duplicados)
            $user = User::where('google_id', $googleUser->getId())->first();
    
            // Si no existe, buscar por email
            if(!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                
                // Si existe usuario con ese email pero sin google_id, actualizar
                if($user) {
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    // Crear nuevo usuario
                    $user = User::create([
                        'nombre' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => bcrypt(uniqid()), 
                        'informacion_completa' => false,
                        'email_verified_at' => now(), 
                    ]);
                }
            }
    
            // Autenticar y recordar sesión
            Auth::login($user, true); // <- El segundo parámetro 'true' activa "remember me"

            // Redirección inteligente
            return $user->informacion_completa 
                ? redirect()->route('home')
                : redirect()->route('encuesta');
    
        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
        
            return redirect()->route('login')->withErrors([
                'google' => 'Error al autenticar. Intenta nuevamente.'
            ]);
        }
    }
}