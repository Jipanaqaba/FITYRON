<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RutinaEjercicio extends Model
{
    use HasFactory;

    protected $table = 'rutina_ejercicios'; 
    protected $fillable = ['nombre', 'usuario_id'];

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function ejercicios() {
        return $this->belongsToMany(Ejercicio::class, 'rutina_series_reps')
        ->using(RutinaSeriesReps::class)
        ->withPivot('series', 'repeticiones');
    }
}
