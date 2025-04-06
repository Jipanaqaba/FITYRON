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
        Schema::create('ejercicios',function(Blueprint $table){
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->enum('dificultad', ['principiante', 'intermedio', 'avanzado']);
            $table->string('gifUrl');
            $table->string('imgUrl');
            $table->string('videoUrl')->nullable();
            $table->foreignId('parte_cuerpo_id')->constrained('partes_cuerpo');
            $table->foreignId('musculo_objetivo_id')->constrained('musculo_objetivo');
            $table->foreignId('equipo_id')->constrained('equipamiento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};
