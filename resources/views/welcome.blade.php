<!DOCTYPE html>
<html>
<head>
    <title>FITYRON - La mejor app del mundo</title>
    <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    width: 100%;
}

.container {
    text-align: center;
    background-image: url('/img/principal.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; 
    width: 100vw;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

        .btn {
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            border-radius: 25px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
        }
        h1, h2 {
            margin: 0;
            color: aliceblue
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>FITYRON</h1>
        <h2>La mejor app del mundo</h2>
        
        <div style="margin-top: 50px;">
            <a href="{{ route('encuesta') }}" class="btn btn-primary">Comenzar</a>
            
            @guest
                <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Cerrar sesión</button>
                </form>
            @endguest
        </div>
    </div>
</body>
</html>