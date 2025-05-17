<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RutinaEjercicio;
use App\Models\Seguimiento;
use App\Models\HistorialChatbot;
//use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * @mixin \Eloquent
 */
//Luego hago verificacion de email para las pruebas finales 
//implements MustVerifyEmail
class User extends Authenticatable  {
    use HasFactory, Notifiable;
    
    protected $table = 'usuarios';
        
    protected $fillable = [
        'nombre', 'email','password','google_id','edad', 'genero', 'objetivo', 'peso', 'altura', 
        'experiencia', 'informacion_completa', 'lesiones', 'duracion_entrenamiento',
        'tiempo_entrenamiento', 'lugar_entrenamiento','dias_entrenamiento' 
    ];
    protected $hidden = [
        'password',       
        'remember_token',  
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dias_entrenamiento' => 'array',  
    ];

    public function rutinas() {
        return $this->hasMany(RutinaEjercicio::class, 'usuario_id');
    }
    
    public function seguimientos() {
        return $this->hasMany(Seguimiento::class);
    }
    
    public function historialChatbot() {
        return $this->hasMany(HistorialChatbot::class, 'usuario_id');
    }
}