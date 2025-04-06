<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialChatbot extends Model
{
    use HasFactory;

    protected $table ='historial_chatbot';
    protected $fillable = [ 'usuario_id','mensaje_usuario', 'respuesta_ia','metadata'];

    public function usuario() {
        return $this->belongsTo(User::class);
    }
}
