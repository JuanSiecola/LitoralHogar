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
        Schema::create('detalle_inmobiliaria', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('rut')->unique();
            $table->string('direccion');
            $table->string('logo_url')->nullable();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_inmobiliaria');
    }
};
