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
        Schema::create('propiedad_vistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedad')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->date('fecha');
            $table->timestamps();

            // esto para que cuente una visita única por sesión, por propiedad, por día
            $table->unique(['propiedad_id', 'session_id', 'fecha']);
            $table->index(['propiedad_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedad_vistas');
    }
};
