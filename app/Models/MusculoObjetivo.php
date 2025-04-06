<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusculoObjetivo extends Model
{
    use HasFactory;

    protected $table = 'musculo_objetivo';
    
    protected $fillable = ['nombre_api', 'nombre_mostrar'];

    public function ejercicios() {
        return $this->hasMany(Ejercicio::class);
    }
}
