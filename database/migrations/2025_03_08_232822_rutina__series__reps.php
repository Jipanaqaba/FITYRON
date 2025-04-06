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
        Schema::create('rutina_series_reps',function(Blueprint $table){
            $table->id();
            $table->integer('series');
            $table->integer('repeticiones');
            $table->foreignId('rutina_ejercicio_id')->constrained('rutina_ejercicios');
            $table->foreignId('ejercicio_id')->constrained('ejercicios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutina_series_reps'); 
    }
};
