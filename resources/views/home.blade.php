@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<div class="home-container">
    <!-- Saludo + Info usuario -->
    <div class="header-section">
        <div class="welcome-message">
            <h1>¡Hola, {{ strtoupper(Auth::user()->nombre) }}!</h1>
            <p>Listo para un nuevo día de progreso?</p>
            <p>"No se trata de ser el mejor, se trata de ser mejor que ayer."</p>
        </div>

        <div class="user-info">
            <ul>
                <li><strong>Género:</strong> {{ Auth::user()->genero ?? 'No especificado' }}</li>
                <li><strong>Objetivo:</strong> {{ Auth::user()->objetivo ?? 'No especificado' }}</li>
                <li><strong>Experiencia:</strong> {{ Auth::user()->experiencia ?? 'No especificado' }}</li>
                <li><strong>Lesiones:</strong> {{ Auth::user()->lesiones ?? 'No especificado' }}</li>
                @php
                 $dias_entrenamiento = Auth::user()->dias_entrenamiento;
                 $dias = 'No especificado';
                 if (is_array($dias_entrenamiento)) {
                     // Mapa de días a sus iniciales
                     $mapa_dias = [
                         'lunes' => 'L',
                         'martes' => 'M',
                         'miercoles' => 'X',
                         'jueves' => 'J',
                         'viernes' => 'V',
                         'sabado' => 'S',
                         'domingo' => 'D',
                     ];

                     // Filtrar y mapear los días a sus iniciales
                    $dias_iniciales = array_map(function($dia) use ($mapa_dias) {
                        return isset($mapa_dias[$dia]) ? $mapa_dias[$dia] : null;
                    }, array_filter(array_map('trim', $dias_entrenamiento)));
                    $dias_iniciales = array_filter($dias_iniciales);
                    // Si hay días seleccionados, unirlos en una cadena
                    if (!empty($dias_iniciales)) {
                        $dias = implode(', ', $dias_iniciales);
                    }
                }
                @endphp
                <li><strong>Días de entrenamiento:</strong> </br>{{ $dias }}</li>
                <li><strong>Lugar de entrenamiento:</strong> {{ Auth::user()->lugar_entrenamiento ?? 'No especificado' }}</li>
            </ul>
        </div>
    </div>

    <!-- Rutina y gráfico -->
   <div class="content-section">
 <div class="workout-cards">
    <h1>
        Tus Rutinas durante {{ Auth::user()->tiempo_entrenamiento ?? 'No especificado' }} meses
    </h1>

    @if($rutinas && $rutinas->isNotEmpty())
        @foreach($rutinas as $rutina)
            @php
                $ejerciciosPorDia = $rutina->ejercicios->groupBy('pivot.dia');
                $diasSemana = [
                    'L' => 'lunes', 
                    'M' => 'martes', 
                    'X' => 'miercoles',
                    'J' => 'jueves', 
                    'V' => 'viernes', 
                    'S' => 'sabado', 
                    'D' => 'domingo'
                ];
                $diaActual = now()->locale('es')->isoFormat('dddd');
            @endphp
@php
    if (!function_exists('normalizarDia')) {
        function normalizarDia($dia) {
            $dia = str_replace(
                ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
                ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
                strtolower($dia)
            );
            return $dia;
        }
    }

    // Obtener el día actual normalizado
    $diaActualNormalizado = normalizarDia(now()->locale('es')->isoFormat('dddd'));
@endphp
            @foreach($ejerciciosPorDia as $dia => $ejerciciosDia)
            
               @php
        $nombreDia = $diasSemana[$dia] ?? $dia;
        $nombreDiaNormalizado = normalizarDia($nombreDia);
        $esHoy = $diaActualNormalizado === $nombreDiaNormalizado;
    @endphp

                        <div class="routine-card">
                    <div class="routine-header">
                        <!-- Título combinado -->
                        <h2 class="routine-title">
                            {{ $rutina->nombre }} - {{ ucfirst($nombreDia) }}
                        </h2>

                  @if($esHoy)
                    <a href="{{ route('rutina.ejecutar', ['dia' => $dia]) }}" 
                       class="start-workout-btn active">
                        Comenzar ahora
                    </a>
                @else
                    <a href="{{ route('rutina.dia', ['dia' =>  strtolower($nombreDiaNormalizado)]) }}" 
                       class="start-workout-btn">
                        Ver detalle
                    </a>
                @endif
            
                    </div>
                </div>
            @endforeach
        @endforeach
    @else
        <p>No tienes rutinas generadas aún. Haz clic en "Generar Mi Rutina" para empezar.</p>
        <form action="{{ route('generar.rutina') }}" method="POST">
            @csrf
            <button type="submit" class="start-workout-btn">
                <p>Generar Mi Rutina</p>
            </button>
        </form>
    @endif
</div>

        <div class="weekly-stats">
            <h3>Sesión de Entrenamiento </h3>
            <canvas id="statsChart"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const statsChart = document.getElementById('statsChart').getContext('2d');
    const chart = new Chart(statsChart, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!}, // Etiquetas del gráfico (días de la semana)
            datasets: [{
                label: 'Sesiones completadas',
                data: {!! json_encode($datos) !!}, // Datos (número de sesiones por día)
                backgroundColor: '#22c55e', 
                borderRadius: 10 
            }]
        },
        options: {
            scales: {
                x: {
                    ticks: {
                        color: '#ffffff' 
                    }
                },
                y: {
                    beginAtZero: true, 
                    ticks: {
                        stepSize: 1, 
                        color: '#ffffff' 
                    }
                }
            }
        }
    });
</script>


@include('components.navbarInf')
@endsection