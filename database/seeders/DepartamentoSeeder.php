<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            'Artigas',
            'Canelones',
            'Cerro Largo',
            'Colonia',
            'Durazno',
            'Flores',
            'Florida',
            'Lavalleja',
            'Maldonado',
            'Montevideo',
            'Paysandú',
            'Río Negro',
            'Rivera',
            'Rocha',
            'Salto',
            'San José',
            'Soriano',
            'Tacuarembó',
            'Treinta y Tres',
        ];
        foreach ($departamentos as $nombre){
            Departamento::create(['nombre' => $nombre]);
        }
    }
}
