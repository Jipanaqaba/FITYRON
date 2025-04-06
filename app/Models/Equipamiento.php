<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Equipamiento extends Model
{
    use HasFactory;
    protected $table = 'equipamiento';
    protected $fillable =['nombre_api', 'nombre_mostrar'];

    public function ejercicios() {
        return $this->hasMany(Ejercicio::class);
    }
}
