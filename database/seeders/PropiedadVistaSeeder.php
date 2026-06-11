<?php

namespace Database\Seeders;

use App\Models\Propiedad;
use App\Models\PropiedadVista;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PropiedadVistaSeeder extends Seeder
{
    public function run(): void
    {
        $propiedades = Propiedad::pluck('id');

        if ($propiedades->isEmpty()) {
            $this->command->warn('No hay propiedades. Creá propiedades antes de correr este seeder.');
            return;
        }

        $filas = [];

        foreach ($propiedades as $propiedadId) {
            for ($i = 0; $i < 30; $i++) {
                $fecha = Carbon::today()->subDays($i);

                $cantidad = random_int(0, 8);

                for ($v = 0; $v < $cantidad; $v++) {
                    // Hora aleatoria dentro del día (entre 8 y 22 hs)
                    $momento = $fecha->copy()->setTime(random_int(8, 22), random_int(0, 59));

                    $filas[] = [
                        'propiedad_id' => $propiedadId,
                        'usuario_id'   => null,
                        'session_id'   => (string) Str::uuid(), 
                        'ip'           => '127.0.0.1',
                        'fecha'        => $fecha->toDateString(),
                        'created_at'   => $momento,
                        'updated_at'   => $momento,
                    ];
                }
            }
        }

        PropiedadVista::insert($filas);

        $this->command->info(count($filas) . ' visitas de prueba generadas (últimos 30 días).');
    }
}