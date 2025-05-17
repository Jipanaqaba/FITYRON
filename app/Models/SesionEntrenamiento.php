<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesionEntrenamiento extends Model
{
    use HasFactory;
    protected $table='sesion_entrenamientos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario_id',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
  