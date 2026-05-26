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
        Schema::table('inmobiliaria', function (Blueprint $table) {
            $table->string('logo_public_id')->nullable()->after('logo_url');
        });

        Schema::table('perfil_persona', function (Blueprint $table) {
            $table->string('foto_public_id')->nullable()->after('foto_url');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmobiliaria', function (Blueprint $table) {
            $table->dropColumn('logo_public_id');
        });

        Schema::table('perfil_persona', function (Blueprint $table) {
            $table->dropColumn('foto_public_id');
        });
    }
};
