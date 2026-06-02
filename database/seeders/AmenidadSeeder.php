<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenidad;

class AmenidadSeeder extends Seeder
{
    public function run(): void
    {
        $amenidades = [
            'Piscina',
            'Parrillero',
            'Jardín',
            'Garage cubierto',
            'Seguridad 24hs',
            'Salón de usos múltiples',
            'Cancha de tenis',
            'Ascensor',
            'Terraza',
            'Lavandería',
            'Portería',
            'Aire acondicionado',
            'Calefacción central',
            'Gimnasio',
            'Sala de juegos infantiles',
            'Wifi',
            'Cocina equipada',
            'Amoblado',
        ];

        foreach ($amenidades as $nombre) {
            Amenidad::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
