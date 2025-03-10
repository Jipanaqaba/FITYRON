<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios',function(Blueprint $table){
            $table->id();
            $table->string('nombre');
            $table->integer('edad');  
            $table->enum('genero', ['masculino', 'femenino']);
            $table->string('objetivo');
            $table->float('peso');
            $table->float('altura');
            $table->enum('experiencia', ['principiante', 'intermedio', 'avanzado']);
            $table->boolean('informacion_completa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
