<!-- home.blade.php -->
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endpush

@section('content')
<div class="container">
    <!-- Header -->
    <header class="main-header">
        <h1>FitAI</h1>
        <button class="free-trial-btn">PRUEBA GRATIS</button>
    </header>

<!-- Oferta -->
<div class="offer-banner">
    <p>¡60% de descuento! ¡Nuestra mejor oferta hasta ahora!</p>
</div>

<!-- Sección de Perfil -->
<section class="profile-section">
    <div class="profile-header">
        <div class="user-info">
          <h2>{{ $userData['nombre'] }}, {{ $userData['edad'] }}</h2>
            <button class="login-btn">Iniciar sesión</button>
        </div>
        <img src="user-avatar.png" alt="Avatar" class="user-avatar">
    </div>

    <div class="weight-progress">
        <div class="progress-info">
            <span>{{ $userData['peso'] }}</span>
            <span>Meta</span>
        </div>
        <div class="progress-bar"></div>
    </div>
</section>

<!-- Calendario -->
<section class="calendar">
    <h3>0 semanas seguidas</h3>
    <div class="week-days">
        <div>dom.</div><div>lun.</div><div>mar.</div>
        <div>mié.</div><div>jue.</div><div>vie.</div><div>sáb.</div>
    </div>
    <div class="week-dates">
        <div>13</div><div>14</div><div>15</div><div>16</div>
        <div>17</div><div>18</div><div>19</div>
    </div>
</section>

<!-- Entrenamientos Completados -->
<section class="completed-workouts">
    <h4>Construcción Muscular miércoles, 5 mar. 2025</h4>
    <div class="workout-stats">
        <div class="stat">
            <span class="value">27 seg</span>
            <span class="label">Duración</span>
        </div>
        <div class="stat">
            <span class="value">30 kg</span>
            <span class="label">Volumen</span>
        </div>
        <div class="stat">
            <span class="value">15 Kcal</span>
            <span class="label">Calorías</span>
        </div>
    </div>
</section>

<!-- Navegación Inferior -->
<nav class="bottom-nav">
    <a href="#" class="nav-item"><i class="fas fa-dumbbell"></i></a>
    <a href="#" class="nav-item"><i class="fas fa-list"></i></a>
    <a href="#" class="nav-item"><i class="fas fa-calendar-alt"></i></a>
    <a href="#" class="nav-item"><i class="fas fa-user"></i></a>
</nav></div>
@endsection