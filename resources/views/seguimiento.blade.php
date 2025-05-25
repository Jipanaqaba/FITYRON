@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/seguimiento.css') }}">

<div class="seguimiento-container">
    <h2 class="seguimiento-title">Historial de Seguimiento</h2>

    @if(session('success'))
        <div class="alert-seguimiento">
            {{ session('success') }}
        </div>
    @endif

    <table class="seguimiento-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Peso</th>
                <th>Medidas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historial as $item)
                <tr>
                    <td>{{ $item->fecha_registro->format('d M Y') }}</td>
                    <td>{{ $item->peso }} kg</td>
                    <td>
                        <ul class="medidas-list">
                            @foreach ($item->medidas as $zona => $valor)
                                <li class="medidas-item">
                                    {{ ucfirst($zona) }}: {{ $valor }} cm
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($necesitaSeguimiento)
        <div class="alert-seguimiento">
            <strong>¡Atención!</strong> No has registrado tus medidas este mes.
            <a href="#" onclick="document.getElementById('registro-form').style.display='block'; this.style.display='none';" class="btn-submit">
                Regístralas ahora
            </a>
        </div>

  <div id="registro-form" class="form-seguimiento" style="display: none;">
    <h3>Registrar Nuevo Seguimiento</h3>
    <form method="POST" action="{{ route('seguimiento.registrar') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Peso (kg)</label>
            <input type="number" step="0.1" name="peso" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Pecho (cm)</label>
            <input type="number" step="0.1" name="medidas[pecho]" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Cintura (cm)</label>
            <input type="number" step="0.1" name="medidas[cintura]" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Brazos (cm)</label>
            <input type="number" step="0.1" name="medidas[brazos]" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Cuello (cm)</label>
            <input type="number" step="0.1" name="medidas[cuello]" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Muslo (cm)</label>
            <input type="number" step="0.1" name="medidas[muslo]" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Pantorrilla (cm)</label>
            <input type="number" step="0.1" name="medidas[pantorrilla]" class="form-input" required>
        </div>

        <input type="hidden" name="usuario_id" value="{{ auth()->user()->id }}">

        <button type="submit" class="btn-submit">Guardar seguimiento</button>
    </form>
</div>
    @endif

    @if ($historial->count())
        <div class="chart-container">
            <canvas id="pesoChart"></canvas>
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  new Chart(document.getElementById('pesoChart'), {
    type: 'line',
    data: {
        labels: @json($historial->pluck('fecha_registro')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
        datasets: [{
            label: 'Progreso de Peso (kg)',
            data: @json($historial->pluck('peso')),
            borderColor: '#625EEB',
            backgroundColor: 'rgba(98, 94, 235, 0.1)',
            borderWidth: 3,  /* Línea más gruesa */
            pointRadius: 6,
            pointHoverRadius: 8,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,  /* Permite ajuste manual */
        layout: {
            padding: {
                top: 20,
                bottom: 20
            }
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 16  /* Texto más grande */
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                grid: {
                    color: '#f0f0f0'
                },
                title: {
                    display: true,
                    text: 'Peso (kg)',
                    font: {
                        size: 14
                    }
                },
                ticks: {
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Fecha',
                    font: {
                        size: 14
                    }
                },
                ticks: {
                    autoSkip: true,
                    maxRotation: 0,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>

@include('components.navbarInf')
@endsection