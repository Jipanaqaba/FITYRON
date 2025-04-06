<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use App\Models\RutinaEjercicio;
use App\Models\Seguimiento;
use App\Models\HistorialChatbot;

/**
 * @mixin \Eloquent
 */
class User extends Authenticatable {
    use HasFactory, Notifiable, HasAttributes;
    
    protected $table = 'usuarios';
        
    protected $fillable = [
        'nombre', 'email','password','edad', 'genero', 'objetivo', 'peso', 'altura', 
        'experiencia', 'informacion_completa'
    ];
    protected $hidden = [
        'password',       
        'remember_token',  
    ];
         
    public function rutinas() {
        return $this->hasMany(RutinaEjercicio::class);
    }
    
    public function seguimientos() {
        return $this->hasMany(Seguimiento::class);
    }
    
    public function historialChatbot() {
        return $this->hasMany(HistorialChatbot::class);
    }
}