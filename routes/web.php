<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Modelo3DController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\RutinaController;
use App\Http\Controllers\SeguimientoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Acceso público a la encuesta (sin autenticación)
Route::get('/encuesta', [EncuestaController::class, 'show'])->name('encuesta');
// Guardar datos de la encuesta temporalmente en sesión (sin autenticación)
Route::post('/encuesta/guest', [EncuestaController::class, 'storeGuest'])->name('encuesta.guest');


Route::middleware(['auth', 'verified'])->group(function () {
    // Encuesta para usuarios registrados (guarda en BD)
    Route::post('/encuesta', [EncuestaController::class, 'store'])->name('guardarEncuesta');
    Route::get('/home', function () { return view('home'); })->name('home');
});


// Página principal sí requiere autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', function () {
        return view('home'); 
    })->name('home');
});



//HOME PAGE
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Ejercicios
Route::post('/rutina/generar', [RutinaController::class, 'generar'])->name('generar.rutina');
Route::get('/rutina/{dia}', [RutinaController::class, 'mostrarDia'])->name('rutina.dia');
Route::get('/rutina/ejecutar/{dia}', [RutinaController::class, 'ejecutarDia'])
    ->name('rutina.ejecutar');
Route::post('/rutina/finalizar', [RutinaController::class, 'finalizar'])
     ->name('rutina.finalizar');

    


//modelo3d
 Route::get('/modelos3d', [Modelo3DController::class, 'index'])->name('modelos3d');
    Route::get('/ejercicios/{parteCuerpo}', [Modelo3DController::class, 'getEjercicios']);
    Route::get('/musculos/{musculoApi}/ejercicios', [Modelo3DController::class, 'getEjerciciosPorMusculo']);
    Route::get('/ejercicios/{parteCuerpo}', [Modelo3DController::class, 'getEjercicios'])
     ->where('parteCuerpo', '^(back|chest|shoulders|upper-arms|lower-legs|upper-legs|cardio)$');
     
//chatbot
  Route::post('/chatbot/enviar', [ChatbotController::class, 'enviarMensaje'])->name('chatbot.enviar');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');

    //seguimiento
        
 Route::get('/seguimiento', [SeguimientoController::class, 'index'])->name('seguimiento');
    Route::post('/seguimiento', [SeguimientoController::class, 'registrar'])->name('seguimiento.registrar');
    
    