<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;

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
