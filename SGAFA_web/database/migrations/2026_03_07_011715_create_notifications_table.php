<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo');        // 'activo_faltante', 'solicitud', 'mantenimiento', 'general'
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('icono')->default('bell');   // bell, warning, check, info
            $table->string('color')->default('blue');   // blue, red, yellow, green
            $table->string('url')->nullable();          // link al recurso relacionado
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};