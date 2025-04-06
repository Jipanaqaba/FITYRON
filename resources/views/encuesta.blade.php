<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>¿Cual es tu nombre?</h1>
    <form action="{{ route('guardarEncuesta') }}" method="POST">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="edad" placeholder="Edad" required>
        <select name="genero" required>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
        </select>
        <!-- Otros campos aquí... -->
        <select name="objetivo">
            <option value="perder grasa">Perder grasa</option>
            <option value="ganar músculo">Ganar músculo</option>
            <option value="mantenerme en forma">Mantenerme en forma</option>
        </select>
        <button type="submit">Guardar Encuesta</button>
    </form>
</body>
</html>