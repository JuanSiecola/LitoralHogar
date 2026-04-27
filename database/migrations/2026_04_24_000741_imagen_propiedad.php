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
        Schema::create('imagen_propiedad', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('public_id');
            $table->integer('orden');
            $table->boolean('es_principal')->default(false);
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_subida')->useCurrent();
            $table->foreignId('propiedad_id')->constrained('propiedad')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_propiedad');
    }
};
