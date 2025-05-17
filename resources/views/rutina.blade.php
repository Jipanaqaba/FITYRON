@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/rutina.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-6 text-center text-white bg-gray-900 min-h-screen">

    <h1 class="text-3xl font-bold mb-6">Sesión de Entrenamiento - {{ $nombreDia }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna izquierda: Estadísticas -->
        <div class="lg:col-span-1 bg-gray-800 p-6 rounded-xl">
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Progreso</h3>
                <div id="exercise-progress" class="text-2xl text-purple-400"></div>
            </div>
            
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Próximos Ejercicios</h3>
                <div id="upcoming-exercises" class="space-y-3"></div>
            </div>
        </div>

        <!-- Columna central: Ejecución -->
        <div class="lg:col-span-2">
            <div id="workout-session" class="bg-gray-800 rounded-xl shadow-lg p-6 transition-all duration-300">
                <h2 id="exercise-name" class="text-2xl font-semibold mb-4"></h2>

                <div class="relative flex justify-center mb-4">
                    <img id="exercise-gif" src="" alt="Ejercicio" 
                         class="rounded-xl shadow-lg w-full max-w-2xl h-auto object-contain aspect-video bg-gray-800 p-2"
                         onerror="this.src='/img/FityronLogo.png'">
                    <div id="timer" class="absolute top-4 right-4 bg-gray-900/80 px-4 py-2 rounded-xl text-3xl font-mono text-white">
                        <span id="time">00:00</span>
                    </div>
                </div>

                <div class="w-full bg-gray-700 rounded-full h-3 mb-4">
                    <div id="progress-bar" class="bg-purple-500 h-3 rounded-full transition-all duration-500" 
                         style="width: 0%"></div>
                </div>

                <div id="status" class="text-xl font-semibold mb-4">Preparado</div>
                
                <button onclick="toggleWorkout()" id="control-button" 
                        class="bg-purple-600 hover:bg-purple-700 px-8 py-3 rounded-xl text-white text-lg font-semibold transition w-full">
                    Comenzar Rutina
                </button>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <button onclick="skipExercise()" 
                            class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-xl text-white font-semibold transition">
                        Saltar Ejercicio
                    </button>
                    <button onclick="skipRest()" 
                            class="bg-orange-600 hover:bg-orange-700 px-6 py-2 rounded-xl text-white font-semibold transition">
                        Saltar Descanso
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    const exercises = @json($ejercicios);
    console.log('Ejercicios cargados:', exercises); // Debugging

    let currentExercise = 0;
    let currentSet = 1;
    let timerInterval;
    let timeLeft;
    let isPaused = true;
    let inRest = false;

    function toggleWorkout() {
        const button = document.getElementById('control-button');
        if (isPaused) {
            startWorkout();
            button.textContent = 'Pausar Rutina';
            button.classList.replace('bg-purple-600', 'bg-red-600');
        } else {
            pauseWorkout();
            button.textContent = 'Reanudar Rutina';
            button.classList.replace('bg-red-600', 'bg-purple-600');
        }
        isPaused = !isPaused;
    }

    function skipExercise() {
        clearInterval(timerInterval);
        if (inRest) return;

        currentSet = exercises[currentExercise].series;
        handleNextStep();
    }

    function skipRest() {
        clearInterval(timerInterval);
        if (!inRest) return;

        timeLeft = 0;
        handleNextStep();
    }

   function startWorkout() {
    if (exercises.length === 0) {
        alert('No hay ejercicios para hoy');
        return;
    }

    updateUpcomingExercises();

    if (!inRest && timeLeft === undefined) {
        // Solo si es el primer inicio
        showExercise();
    } else {
        startTimer(); // Reanudar desde donde quedó
    }
}

    function showExercise() {
        if (currentExercise >= exercises.length) {
            endWorkout();
            return;
        }

        const exercise = exercises[currentExercise];
        inRest = false;
        timeLeft = exercise.duracion_serie;

        console.log(`Ejercicio: ${exercise.nombre}`);
        console.log(`Duración total: ${timeLeft}s`);

        document.getElementById('exercise-name').textContent = exercise.nombre;
        document.getElementById('exercise-gif').src = exercise.gifUrl;
        document.getElementById('status').textContent = `Ejercicio (Serie ${currentSet}/${exercise.series})`;
        updateProgressInfo();

        startTimer();
    }

    function startRest() {
        inRest = true;
        timeLeft = exercises[currentExercise].descanso;

        const nextExercise = currentExercise + 1 < exercises.length
            ? exercises[currentExercise + 1]
            : null;

        document.getElementById('status').textContent = 'Descanso';
        document.getElementById('exercise-name').textContent = nextExercise
            ? `Próximo: ${nextExercise.nombre}`
            : '¡Último descanso!';

        document.getElementById('exercise-gif').src = nextExercise
            ? nextExercise.gifUrl
            : '/images/rest.gif';

        updateProgressInfo();
        startTimer();
    }

    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (!isPaused && timeLeft > 0) {
                timeLeft--;
                updateTimer();
                updateProgressBar();

                if (timeLeft === 5) new Audio('/sounds/sport-metal-90-bpm-loop-13726.mp3').play();
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                handleNextStep();
            }
        }, 1000);
    }

    function handleNextStep() {
        if (timeLeft < 0) {
            timeLeft = 0;
            updateTimer();
        }

        if (inRest) {
            currentSet = 1;
            if (currentExercise + 1 < exercises.length) {
                currentExercise++;
                showExercise();
            } else {
                endWorkout();
            }
        } else {
            currentSet++;
            if (currentSet > exercises[currentExercise].series) {
                startRest();
            } else {
                timeLeft = exercises[currentExercise].duracion_serie;
                document.getElementById('status').textContent = `Ejercicio (Serie ${currentSet}/${exercises[currentExercise].series})`;
                startTimer();
            }
        }
    }

    function updateTimer() {
        if (timeLeft < 0) timeLeft = 0;

        const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const seconds = (timeLeft % 60).toString().padStart(2, '0');
        document.getElementById('time').textContent = `${minutes}:${seconds}`;
    }

    function updateProgressBar() {
        const totalTime = inRest ?
            exercises[currentExercise].descanso :
            exercises[currentExercise].series * exercises[currentExercise].duracion_serie;

        const progress = ((totalTime - timeLeft) / totalTime) * 100;
        document.getElementById('progress-bar').style.width = `${progress}%`;
    }

    function updateProgressInfo() {
        document.getElementById('exercise-progress').innerHTML = `
            <div class="text-lg">Ejercicio ${currentExercise + 1} de ${exercises.length}</div>
            <div class="text-sm text-gray-400">${inRest ? 'Descanso' : 'Series completadas'}: ${currentSet - 1}</div>
        `;
    }

    function updateUpcomingExercises() {
        const container = document.getElementById('upcoming-exercises');
        container.innerHTML = exercises.slice(currentExercise)
            .map((ex, index) => `
                <div class="bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-700 p-4 rounded-2xl shadow-md text-left flex gap-3 items-center">
                    <img src="${ex.gifUrl}" alt="${ex.nombre}" class="w-14 h-14 rounded-lg object-cover bg-white">
                    <div>
                        <div class="font-semibold">${index + 1}. ${ex.nombre}</div>
                        <div class="text-sm text-gray-200">
                            Series: ${ex.series} × ${ex.duracion_serie}s
                        </div>
                    </div>
                </div>
            `).join('');
    }

    function endWorkout() {
        clearInterval(timerInterval);
        document.getElementById('status').textContent = '¡Rutina Completada!';
        const finishForm = document.createElement('form');
        finishForm.method = 'POST';
        finishForm.action = @json(route('rutina.finalizar'));
            
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        finishForm.appendChild(tokenInput);

        document.body.appendChild(finishForm);
        finishForm.submit();
    }
      function pauseWorkout() {
        clearInterval(timerInterval);
    }
</script>
@endsection
