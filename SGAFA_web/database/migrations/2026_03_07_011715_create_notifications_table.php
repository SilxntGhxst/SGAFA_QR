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
            $table->string('user_id');           // UUID del usuario en el sistema FastAPI
            $table->string('tipo');              // 'proroga', 'sistema', 'auditoria'
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('icono')->default('bell');
            $table->string('color')->default('blue');
            $table->string('url')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamps();
            // Índice para búsquedas rápidas por usuario
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};