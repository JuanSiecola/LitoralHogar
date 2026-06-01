<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Localidad;

class LocalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/localidades.json');
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        // Mapa para resolver nombres del JSON que no coinciden con nuestros departamentos
        $alias = [
            // 'Nombre en JSON' => 'Nombre en nuestra BD'
            // Por ahora el JSON de fedebabrauskas viene bien escrito,
            // pero dejamos el mapa por si encontrás algún caso
        ];

        foreach ($data as $depto) {
            $nombreDepto = $alias[$depto['name']] ?? $depto['name'];

            $departamento = Departamento::where('nombre', $nombreDepto)->first();

            if (!$departamento) {
                $this->command->warn("Departamento no encontrado: {$depto['name']}");
                continue;
            }

            foreach ($depto['towns'] as $town) {
                Localidad::create([
                    'nombre' => $town['name'],
                    'departamento_id' => $departamento->id,
                ]);
            }

            $this->command->info("✓ {$departamento->nombre}: " . count($depto['towns']) . " localidades");
        }
    }
}
