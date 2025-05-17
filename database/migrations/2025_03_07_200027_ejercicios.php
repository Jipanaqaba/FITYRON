<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('ejercicios', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion');
        $table->enum('objetivo', ['perder grasa', 'ganar musculo', 'mantenerme en forma'])->nullable();
        $table->enum('dificultad', ['principiante', 'intermedio', 'avanzado']);
        $table->string('gifUrl');
        $table->string('imgUrl');
        $table->string('videoUrl')->nullable();
        
        // Claves foráneas
        $table->foreignId('parte_cuerpo_id')->constrained('partes_cuerpo')->onDelete('cascade');
        $table->foreignId('musculo_objetivo_id')->constrained('musculo_objetivo')->onDelete('cascade');
        $table->foreignId('equipo_id')->constrained('equipamiento')->onDelete('cascade');
        
        $table->json('secondary_muscles')->nullable();
        $table->json('instructions')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('ejercicios');
}
};
