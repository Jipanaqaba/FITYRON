<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Ejercicio;
use App\Models\ParteCuerpo;
use App\Models\MusculoObjetivo;
use App\Models\Equipamiento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportarEjerciciosAPI extends Command
{
    protected $signature = 'ejercicios:importar';
    protected $description = 'Importa ejercicios desde ExerciseDB y los guarda en la base de datos';

    public function handle()
    {
        // Definimos las partes del cuerpo que queremos recorrer
        $partesCuerpo = [
            'back', 'chest', 'lower legs', 'upper arms', 'shoulders', 'upper legs', 'cardio','upper arms'
        ];

        $cnt = 0;

        foreach ($partesCuerpo as $parteCuerpo) {
            $url = "https://exercisedb.p.rapidapi.com/exercises/bodyPart/{$parteCuerpo}?limit=20";
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
                'X-RapidAPI-Host' => 'exercisedb.p.rapidapi.com'
            ])->get($url);

            if (!$response->successful()) {
                $this->error("Fallo al conectar con la API para la parte del cuerpo: {$parteCuerpo}.");
                continue;
            }

            $ejercicios = $response->json();
            $this->info("Ejercicios recibidos para {$parteCuerpo}: " . count($ejercicios));

            foreach ($ejercicios as $item) {
                // Evitar duplicados
                if (Ejercicio::where('nombre', $item['name'])->exists()) continue;

                $parte = ParteCuerpo::firstOrCreate([
                    'nombre_api' => $item['bodyPart'],
                ], ['nombre_mostrar' => ucfirst($item['bodyPart'])]);

                $musculo = MusculoObjetivo::firstOrCreate([
                    'nombre_api' => $item['target'],
                ], ['nombre_mostrar' => ucfirst($item['target'])]);

                $equipo = Equipamiento::firstOrCreate([
                    'nombre_api' => $item['equipment'],
                ], ['nombre_mostrar' => ucfirst($item['equipment'])]);

                // Nueva lógica para asignar la dificultad
                $dificultad = 'principiante';

                // Si usa equipo complejo o el músculo objetivo es avanzado
                if (str_contains(strtolower($item['equipment']), 'barbell') || 
                    str_contains(strtolower($item['equipment']), 'cable') ||
                    str_contains(strtolower($item['equipment']), 'kettlebell') || 
                    str_contains(strtolower($item['target']), 'lower back') ||
                    str_contains(strtolower($item['target']), 'core') ||
                    str_contains(strtolower($item['name']), 'deadlift') ||
                    str_contains(strtolower($item['name']), 'squat')) {
                    $dificultad = 'intermedio';
                }

                // Si usa equipos muy avanzados o el ejercicio es muy desafiante
                if (str_contains(strtolower($item['equipment']), 'barbell') || 
                    str_contains(strtolower($item['name']), 'snatch') || 
                    str_contains(strtolower($item['name']), 'clean') || 
                    str_contains(strtolower($item['name']), 'muscle up')) {
                    $dificultad = 'avanzado';
                }

                $objetivo = 'mantenerme en forma';

                if (
                    str_contains(strtolower($item['bodyPart']), 'cardio') ||
                    str_contains(strtolower($item['target']), 'glutes') ||
                    str_contains(strtolower($item['target']), 'hamstrings') ||
                    str_contains(strtolower($item['target']), 'calves') ||
                    str_contains(strtolower($item['target']), 'quads') ||
                    str_contains(strtolower($item['name']), 'jump') ||
                    str_contains(strtolower($item['name']), 'burpee')
                ) {
                    $objetivo = 'perder grasa';
                }

                if (
                    str_contains(strtolower($item['target']), 'chest') ||
                    str_contains(strtolower($item['target']), 'biceps') ||
                    str_contains(strtolower($item['target']), 'triceps') ||
                    str_contains(strtolower($item['target']), 'lats') ||
                    str_contains(strtolower($item['target']), 'delts') ||
                    str_contains(strtolower($item['target']), 'traps') ||
                    str_contains(strtolower($item['target']), 'glutes') ||
                    str_contains(strtolower($item['name']), 'curl') ||
                    str_contains(strtolower($item['name']), 'press') ||
                    str_contains(strtolower($item['name']), 'pull')
                ) {
                    $objetivo = 'ganar musculo';
                }
// Guardar el gif en local y usar su URL
$gifUrlOriginal = $item['gifUrl'];
$gifFilename = 'gifs/' . Str::slug($item['name']) . '-' . uniqid() . '.gif';

try {
    $gifContent = file_get_contents($gifUrlOriginal);
    if ($gifContent !== false) {
        Storage::disk('public')->put($gifFilename, $gifContent);
        $gifLocalUrl = env('APP_URL') . '/storage/' . $gifFilename;
    } else {
        $this->error("No se pudo descargar el gif para: " . $item['name']);
        $gifLocalUrl = null;
    }
} catch (\Exception $e) {
    $this->error("Error al guardar gif de {$item['name']}: " . $e->getMessage());
    $gifLocalUrl = null;
}

                
                Ejercicio::create([
                    'nombre' => ucfirst($item['name']),
                    'descripcion' => 'Descripción del ejercicio',
                    'dificultad' => $dificultad,
                    'objetivo' => $objetivo,
                    'gifUrl' => $gifLocalUrl,
                    'imgUrl' => '',
                    'videoUrl' => null,
                    'parte_cuerpo_id' => $parte->id,
                    'musculo_objetivo_id' => $musculo->id,
                    'equipo_id' => $equipo->id,
                    'secondary_muscles' => isset($item['secondaryMuscles']) ? json_encode($item['secondaryMuscles']) : json_encode([]), // Asigna un array vacío si no existe
                    'instructions' => isset($item['instructions']) ? json_encode($item['instructions']) : json_encode([]), // Asigna un array vacío si no existe
                ]);
                
                $cnt++;
            }
        }

        $this->info("Ejercicios nuevos insertados: $cnt");
        $this->info('Ejercicios importados con éxito.');
    }
}