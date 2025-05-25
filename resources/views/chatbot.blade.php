@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="chat-layout">
    <aside class="chat-sidebar">
        <div class="sidebar-header">
            <h2>Fityron AI</h2>
            <p>Tu asistente de fitness</p>
        </div>
        <div class="ejemplo-preguntas">
            <h3>Preguntas frecuentes:</h3>
            @foreach(config('chatbot_templates.respuestas.ejemplo_preguntas') as $pregunta)
                <div class="pregunta-ejemplo" onclick="insertarPregunta('{{ $pregunta }}')">
                    {{ $pregunta }}
                </div>
            @endforeach
        </div>
    </aside>

    <main class="chat-main">
        <div id="chat-box" class="chat-box">
            <div class="mensaje-ia">
                <div class="burbuja-ia">{{ $saludo }}</div>
            </div>
        </div>

        <div class="chat-input-group">
            <input type="text" id="mensaje" placeholder="Escribe tu pregunta sobre fitness..." 
                   onkeypress="handleEnter(event)">
            <button onclick="enviarMensaje()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </main>
</div>

<script>
let isLoading = false;

async function enviarMensaje() {
    if (isLoading) return;
    
    const input = document.getElementById('mensaje');
    const mensaje = input.value.trim();
    if (!mensaje) return;
    input.value = '';
    
    isLoading = true;
    input.disabled = true;
    
    // Agregar mensaje usuario
    const userMsg = `<div class="mensaje-usuario"><div class="burbuja-usuario">${mensaje}</div></div>`;
    document.getElementById('chat-box').insertAdjacentHTML('beforeend', userMsg);
    
    // Agregar loader
    const loaderId = `loader-${Date.now()}`;
    const loader = `<div class="mensaje-ia" id="${loaderId}"><div class="burbuja-ia cargando"><div class="loader"></div></div></div>`;
    document.getElementById('chat-box').insertAdjacentHTML('beforeend', loader);
    
    // Scroll suave
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });

    try {
        const response = await fetch("{{ route('chatbot.enviar') }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ mensaje })
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const data = await response.json();
        
        // Reemplazar loader
      const respuestaFormateada = data.respuesta.replace(/\n/g, '<br>');

document.getElementById(loaderId).outerHTML = `
    <div class="mensaje-ia">
        <div class="burbuja-ia">${respuestaFormateada}</div>
    </div>
`;


    } catch (error) {
        document.getElementById(loaderId).outerHTML = `
            <div class="mensaje-ia">
                <div class="burbuja-ia error">⚠️ Error: ${error.message}</div>
            </div>
        `;
    } finally {
        isLoading = false;
        input.disabled = false;
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
    }
}

function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        enviarMensaje();
    }
}

function insertarPregunta(pregunta) {
    const input = document.getElementById('mensaje');
    input.value = pregunta;
    input.focus();
    enviarMensaje(); // Envía automáticamente
}
</script>
@include('components.navbarInf')

@endsection