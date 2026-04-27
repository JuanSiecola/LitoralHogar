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
        Schema::create('propiedad', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('estado_propiedad', ['Disponible', 'Vendida', 'Alquilada', 'Reservada', 'Pausada']);
            $table->enum('tipo_propiedad', ['Casa', 'Apartamento', 'Local', 'Terreno', 'Oficina']);
            $table->enum('tipo_operacion', ['Venta', 'Alquiler']);
            $table->float('calificacion')->nullable();
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedad');
    }
};
