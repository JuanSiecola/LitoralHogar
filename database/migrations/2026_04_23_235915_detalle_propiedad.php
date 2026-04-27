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
        Schema::create('detalle_propiedad', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_habitaciones');
            $table->integer('nro_banios');
            $table->integer('nro_garage');
            $table->float('superficie_total');
            $table->integer('pisos');
            $table->float('precio');
            $table->integer('anio_construccion');
            $table->enum('estado_construccion', ['Nuevo', 'Usado', 'Reciclar']);
            $table->float('deposito')->nullable();
            $table->integer('cant_meses_deposito')->nullable();
            $table->float('expensas')->nullable();
            $table->boolean('acepta_mascotas')->default(false);
            $table->foreignId('propiedad_id')->unique()->constrained('propiedad')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_propiedad');
    }
};
