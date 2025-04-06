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
        'parte_cuerpo_id', 'musculo_objetivo_id', 'equipo_id'
    ];

    public function parteCuerpo() {
        return $this->belongsTo(ParteCuerpo::class);
    }

    public function musculoObjetivo() {
        return $this->belongsTo(MusculoObjetivo::class);
    }

    public function equipamiento() {
        return $this->belongsTo(Equipamiento::class);
    }
    public function rutinas() {
        return $this->belongsToMany(RutinaEjercicio::class, 'rutina_series_reps')
        ->using(RutinaSeriesReps::class)
        ->withPivot('series', 'repeticiones');
    }

}
