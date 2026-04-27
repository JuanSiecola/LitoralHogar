<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amenidad_propiedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedad')->cascadeOnDelete();
            $table->foreignId('amenidad_id')->constrained('amenidad')->cascadeOnDelete();
            $table->unique(['propiedad_id', 'amenidad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenidad_propiedad');
    }
};
