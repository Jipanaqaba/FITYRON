@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/rutina_dia.css') }}">

<div class="container">
    <h1 class="text-center mb-5" style="font-weight: 700; font-size: 2.2rem;">Ejercicios para el día {{ ucfirst($nombreDia) }}</h1>

    @if($ejercicios->isEmpty())
        <p class="text-center">No hay ejercicios asignados para este día.</p>
    @else
        <div class="ejercicios-grid">
            @foreach($ejercicios as $e)
                <div class="ejercicio-item gradient-card">
                    <div class="ejercicio-content">
                        <div class="ejercicio-nombre">{{ $e->nombre }}</div>
                        <div class="ejercicio-info">
                            {{ $e->pivot->series }} series<br>
                            {{ $e->pivot->repeticiones }} repeticiones
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@include('components.navbarInf')
@endsection
