<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguimiento extends Model
{
    use HasFactory;
    protected $table='seguimiento';
    protected $casts = [
        'medidas' => 'array',
        'fecha_registro' => 'date'
    ];

    protected $fillable = ['peso', 'medidas', 'fecha_registro', 'usuario_id'];

    public function usuario() {
        return $this->belongsTo(User::class);
    }
}
