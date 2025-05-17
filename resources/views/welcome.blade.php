<!DOCTYPE html>
<html>
<head>
    <title>FITYRON - La mejor app del mundo</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<style>
    h1,h2{
        color: #E8EAFA;
        text-align: center;
    }
</style>
<body>
    <div class="container">
        <h1>FITYRON</h1>
        
        <div style="margin-top: 50px;">
            <a href="{{ route('encuesta') }}" class="btn btn-primary">Comenzar</a>
            
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Cerrar sesión</button>
                </form>
            @endguest
        </div>
    </div>
</body>
</html>