<?php

namespace App\Http\Controllers;

use App\Models\HistorialChatbot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot', [
            'saludo' => str_replace(
                '{nombre}', 
                Auth::user()->nombre, 
                config('chatbot_templates.respuestas.saludo')
            )
        ]);
    }

    public function enviarMensaje(Request $request)
    {
        $request->validate(['mensaje' => 'required|string|max:800']);
        
        $usuario = Auth::user();
        $mensaje = $request->input('mensaje');
        
        if (!$this->esPreguntaFitness($mensaje)) {
            return response()->json([
                'respuesta' => config('chatbot_templates.respuestas.fuera_tema')
            ]);
        }
        
        $respuesta = $this->obtenerRespuesta($mensaje);
        
         HistorialChatbot::create([
         'usuario_id' => $usuario->id,
         'mensaje_usuario' => $mensaje,
         'respuesta_ia' => $respuesta,
         'metadata' => [
             'tokens' => strlen($respuesta) / 4,
             'modelo' => config('services.openai.model')
             ]
        ]);

        
        
        return response()->json(compact('respuesta'));
    }

private function esPreguntaFitness(string $mensaje): bool
{
    // Normalización avanzada
    $mensaje = mb_strtolower(trim($mensaje));
    $mensaje = preg_replace('/[¿?¡!]/u', '', $mensaje); // Eliminar signos de pregunta
    
    $preguntasEjemplo = array_map(function($pregunta) {
        return mb_strtolower(preg_replace('/[¿?]/u', '', $pregunta));
    }, config('chatbot_templates.respuestas.ejemplo_preguntas'));
    
    return in_array($mensaje, $preguntasEjemplo) || 
           preg_match('/(rutina|ejercicio|nutrición|entrenar|peso|salud)/i', $mensaje);
}
// En ChatbotController.php
private function obtenerRespuesta(string $mensaje): string
{
    try {
        $response = Http::withToken(config('services.openai.key'))
            ->timeout(25)
            ->retry(2, 1000)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres Fityron, experto en fitness. Responde máximo en 1000 palabras. Idioma: español."
                    ],
                    ['role' => 'user', 'content' => $mensaje]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.4
            ]);
 // Verifica si la respuesta tiene la estructura esperada
        if (!isset($response->json()['choices'][0]['message']['content'])) {
            throw new \Exception("Estructura de respuesta inesperada de OpenAI");
        }
        // Verificar errores de API
        if ($response->failed()) {
            $error = $response->json()['error']['code'] ?? 'unknown_error';
            throw new \Exception("Error OpenAI: $error");
        }

        return $response->json()['choices'][0]['message']['content'];

    } catch (\Exception $e) {
        return "🔧 Error temporal. Por favor, reformula tu pregunta.";
    }
}
}