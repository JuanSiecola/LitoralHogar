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
        Schema::create('localidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('departamento_id')->constrained('departamento')->cascadeOnDelete();
            $table->unique(['departamento_id', 'nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localidad');
    }
};
