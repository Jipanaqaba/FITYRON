<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RutinaSeriesReps extends Pivot
{
    protected $table = 'rutina_Series_Reps';
    public $timestamps = false; 

    protected $fillable = [
        'rutina_ejercicio_id',
        'ejercicio_id',
        'series',
        'repeticiones',
        'dia'
    ];

   
    protected $casts = [
        'series' => 'integer',
        'repeticiones' => 'integer'
    ];
}
