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
        Schema::table('ubicacion', function (Blueprint $table) {
            $table->dropColumn(['localidad', 'departamento', 'latitud', 'longitud']);
        });

        Schema::table('ubicacion', function (Blueprint $table) {
            $table->foreignId('departamento_id')
                  ->after('direccion')
                  ->constrained('departamento')
                  ->cascadeOnDelete();

            $table->foreignId('localidad_id')
                  ->after('departamento_id')
                  ->constrained('localidad')
                  ->cascadeOnDelete();

            $table->decimal('latitud', 10, 8)->nullable()->after('localidad_id');
            $table->decimal('longitud', 11, 8)->nullable()->after('latitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ubicacion', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->dropForeign(['localidad_id']);
            $table->dropColumn(['departamento_id', 'localidad_id', 'latitud', 'longitud']);
        });

        Schema::table('ubicacion', function (Blueprint $table) {
            $table->string('ciudad')->after('direccion');
            $table->string('departamento')->after('ciudad');
            $table->float('longitud')->after('departamento');
            $table->float('latitud')->after('longitud');
        });
    }
};