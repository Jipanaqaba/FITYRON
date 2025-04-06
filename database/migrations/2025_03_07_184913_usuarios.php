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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('edad');
            $table->enum('genero', ['masculino', 'femenino']);
            $table->enum('objetivo', ['perder grasa', 'ganar músculo', 'mantenerme en forma']);
            $table->decimal('peso', 5, 2);
            $table->decimal('altura', 5, 2);
            $table->enum('experiencia', ['principiante', 'intermedio', 'avanzado']);
            $table->boolean('informacion_completa')->default(false);
            $table->rememberToken();
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
