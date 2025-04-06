<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ParteCuerpo;

class Modelo3D extends Model
{
    use HasFactory;

    protected $table = 'modelo_3d';
    
    protected $fillable = ['modelo_path', 'partes_cuerpo_id', 'ejercicios_id'];

    public function ejercicio() {
        return $this->belongsTo(Ejercicio::class, 'ejercicios_id');
    }

    public function parteCuerpo() {
        return $this->belongsTo(ParteCuerpo::class);
    }
}
