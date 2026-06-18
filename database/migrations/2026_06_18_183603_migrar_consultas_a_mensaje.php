<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $consultas = DB::table('consultas')->get();

        foreach ($consultas as $consulta) {
            // Mensaje 1: el mensaje original del cliente
            if (!empty($consulta->mensaje)) {
                DB::table('mensajes_consulta')->insert([
                    'consulta_id' => $consulta->id,
                    'usuario_id' => $consulta->usuario_id,
                    'mensaje' => $consulta->mensaje,
                    'leido' => true,
                    'created_at' => $consulta->created_at,
                    'updated_at' => $consulta->created_at,
                ]);
            }

            // Mensaje 2: la respuesta, si existe, escrita por el dueño de la propiedad
            if (!empty($consulta->respuesta)) {
                $propiedad = DB::table('propiedad')->where('id', $consulta->propiedad_id)->first();

                if ($propiedad) {
                    DB::table('mensajes_consulta')->insert([
                        'consulta_id' => $consulta->id,
                        'usuario_id' => $propiedad->usuario_id,
                        'mensaje' => $consulta->respuesta,
                        'leido' => true,
                        'created_at' => $consulta->updated_at,
                        'updated_at' => $consulta->updated_at,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
    }
};