@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/modelo3d.css') }}">
<div class="container">
    <div class="modelo-container">
        <div id="contenedor3d" style="width: 65%; height: 600px; position: relative;"></div>
        <div id="panel-ejercicios" style="width: 35%; padding: 20px; background: #f8f9fa; border-left: 1px solid #dee2e6;">
            <h4>Ejercicios para: <span id="musculo-seleccionado">-</span></h4>
            <div id="lista-ejercicios" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>

@vite(['resources/js/app.js']);
@vite(['resources/js/modelos3d.js']);


@include('components.navbarInf')
@endsection