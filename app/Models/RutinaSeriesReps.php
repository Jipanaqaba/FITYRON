<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutinaSeriesReps extends Model
{
    protected $table = 'rutina_Series_Reps';

    protected $fillable = [
        'rutina_ejercicio_id',
        'ejercicio_id',
        'series',
        'repeticiones'
    ];

   
    protected $casts = [
        'series' => 'integer',
        'repeticiones' => 'integer'
    ];
}
