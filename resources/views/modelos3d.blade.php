@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modelo3d.css') }}">
<div class="container">
    <div class="modelo-container">
        <div id="contenedor3d" style="width: 70%; height: 600px; position: relative;"></div>
        <div id="panel-ejercicios" style="width: 30%; padding: 20px; background: #f8f9fa; border-left: 1px solid #dee2e6;">
            <h4>Ejercicios para: <span id="musculo-seleccionado">-</span></h4>
            <div id="lista-ejercicios" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>

@vite(['resources/js/app.js']);
@vite(['resources/js/modelos3d.js']);

<style>
    .modelo-container {
        display: flex;
        gap: 20px;
    }
    
    #panel-ejercicios {
        height: 600px;
        overflow-y: auto;
    }
    .instructions {
    padding-left: 20px;
    margin: 10px 0;
    line-height: 1.6;
}

.instructions li {
    margin-bottom: 8px;
    counter-increment: step-counter;
    position: relative;
}

.instructions li:before {
    content: counter(step-counter);
    position: absolute;
    left: -25px;
    background: #2196F3;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    text-align: center;
    font-size: 12px;
    line-height: 20px;
}

/* Mejorar tarjetas de ejercicio */
.ejercicio-card {
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: transform 0.2s;
}

.ejercicio-card:hover {
    transform: translateY(-3px);
}

.ejercicio-card img {
    border: 2px solid #eee;
    border-radius: 8px;
}
</style>
@include('components.navbarInf')
@endsection