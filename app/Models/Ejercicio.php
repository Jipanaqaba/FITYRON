<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParteCuerpo;
use App\Models\MusculoObjetivo;
use App\Models\Equipamiento;
use App\Models\RutinaEjercicio;

class Ejercicio extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre', 'descripcion', 'dificultad', 'gifUrl', 'imgUrl', 'videoUrl',
        'parte_cuerpo_id', 'musculo_objetivo_id', 'equipo_id','secondary_muscles', 'instructions', 'objetivo'
    ];
    protected $casts = [
        'secondary_muscles' => 'array',
        'instructions' => 'array',
    ];
    public function parteCuerpo() {
        return $this->belongsTo(ParteCuerpo::class,'parte_cuerpo_id');
    }

    public function musculoObjetivo() {
        return $this->belongsTo(MusculoObjetivo::class,'musculo_objetivo_id');
    }

    public function equipamiento() {
        return $this->belongsTo(Equipamiento::class,'equipo_id');
    }
    public function rutinas() {
        return $this->belongsToMany(RutinaEjercicio::class, 'rutina_series_reps')
        ->using(RutinaSeriesReps::class)
        ->withPivot('series', 'repeticiones');
    }

}