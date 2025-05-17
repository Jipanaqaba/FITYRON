<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Fitness</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<div class="form-container">
    <div class="header-logo-title">
        <img src="{{ asset('img/FityronLogo.png') }}" alt="Fityron Logo" class="logo">
        <h1>Bienvenido a Fityron</h1>
    </div>
    
    <h2>Cuentanos un poco de ti:</h2>
    @if(Auth::check())
    <form action="{{ route('guardarEncuesta') }}" method="POST">
@else
    <form action="{{ route('encuesta.guest') }}" method="POST">
@endif
    @csrf
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="edad">Edad</label>
        <input type="number" name="edad" id="edad" required>

        <label for="genero">Género</label>
        <select name="genero" id="genero" required>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
        </select>

        <label for="objetivo">Objetivo</label>
        <select name="objetivo" id="objetivo" required>
            <option value="perder grasa">Perder grasa</option>
            <option value="ganar músculo">Ganar músculo</option>
            <option value="mantenerme en forma">Mantenerme en forma</option>
        </select>

        <div class="slider-container">
            <label for="peso">Peso (kg):</label>
            <input type="range" id="peso" name="peso" min="30" max="200" step="0.5" value="70" oninput="pesoValue.innerText = this.value + ' kg'">
            <div class="slider-value" id="pesoValue">70 kg</div>
        </div>

        <div class="slider-container">
            <label for="altura">Altura (cm):</label>
            <input type="range" id="altura" name="altura" min="130" max="220" step="1" value="170" oninput="alturaValue.innerText = this.value + ' cm'">
            <div class="slider-value" id="alturaValue">170 cm</div>
        </div>

        <label for="experiencia">Nivel de experiencia</label>
        <select name="experiencia" id="experiencia" required>
            <option value="principiante">Principiante</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
        </select>
<label for="lugar_entrenamiento">¿Dónde entrenarás?</label>
<select name="lugar_entrenamiento" id="lugar_entrenamiento" required>
    <option value="gimnasio">Gimnasio</option>
    <option value="casa">Casa</option>
    <option value="aire_libre">Aire libre</option>
</select>

<label>¿Qué días puedes entrenar? (Selecciona todos los que apliquen)</label>
<div class="checkbox-group">
    <label><input type="checkbox" name="dias_entrenamiento[]" value="lunes"> Lunes</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="martes"> Martes</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="miercoles"> Miércoles</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="jueves"> Jueves</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="viernes"> Viernes</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="sabado"> Sábado</label>
    <label><input type="checkbox" name="dias_entrenamiento[]" value="domingo"> Domingo</label>
</div>
        <label for="lesiones">¿Tienes alguna lesión?</label>
        <textarea name="lesiones" id="lesiones" rows="3" placeholder="Describe tus lesiones si tienes alguna..."></textarea>

        <label for="duracion_entrenamiento">Duración del entrenamiento (minutos)</label>
        <select name="duracion_entrenamiento" id="duracion_entrenamiento" required>
            <option value="30">30</option>
            <option value="45">45</option>
            <option value="60">60</option>
        </select>

        <label for="tiempo_entrenamiento">¿Cuánto tiempo quieres entrenar (en meses)?</label>
        <select name="tiempo_entrenamiento" id="tiempo_entrenamiento" required>
            <option value="1">1 mes</option>
            <option value="3">3 meses</option>
            <option value="6">6 meses</option>
            <option value="12">12 meses</option>
        </select>

        <button type="submit">Guardar Encuesta</button>
    </form>
    @if(!Auth::check())
    <div class="alert-info">
        ¿Quieres guardar tu progreso? <a href="{{ route('register') }}">Regístrate aquí</a>
    </div>
@endif
</form>
</div>

</body>
</html>
