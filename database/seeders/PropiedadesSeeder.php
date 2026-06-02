<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Propiedad;
use App\Models\DetallePropiedad;
use App\Models\Ubicacion;
use App\Models\Amenidad;
use App\Models\Departamento;
use App\Models\Localidad;

class PropiedadesSeeder extends Seeder
{
    public function run(): void
    {
        $agentes = User::whereHas('rol_usuario', fn ($q) => $q->where('nombre', 'agente'))->get();

        if ($agentes->isEmpty()) {
            $this->command->warn('No se encontraron agentes. Ejecutá UsuariosSeeder primero.');
            return;
        }

        $depto = fn(string $nombre) => Departamento::where('nombre', $nombre)->value('id');
        $loc   = fn(string $nombre, int $deptoId) => Localidad::where('nombre', $nombre)
            ->where('departamento_id', $deptoId)
            ->value('id');

        $propiedades = [
            // ---- Agente 0 ----
            [
                'agente_index' => 0,
                'propiedad' => ['titulo' => 'Casa con jardín en Pocitos', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Casa', 'tipo_operacion' => 'Venta', 'calificacion' => 4.5],
                'ubicacion'  => ['direccion' => 'Av. Brasil 2500', 'localidad' => 'Montevideo', 'departamento' => 'Montevideo', 'longitud' => -56.1645, 'latitud' => -34.9011],
                'detalle'    => ['nro_habitaciones' => 3, 'nro_banios' => 2, 'nro_garage' => 1, 'superficie_total' => 180, 'pisos' => 2, 'precio' => 250000, 'anio_construccion' => 2005, 'estado_construccion' => 'Usado', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => null, 'acepta_mascotas' => true],
                'amenidades' => ['Jardín', 'Parrillero', 'Garage cubierto'],
            ],
            [
                'agente_index' => 0,
                'propiedad' => ['titulo' => 'Apartamento moderno en Centro', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Apartamento', 'tipo_operacion' => 'Alquiler', 'calificacion' => 4.0],
                'ubicacion'  => ['direccion' => 'Av. 18 de Julio 1456, Piso 8', 'localidad' => 'Montevideo', 'departamento' => 'Montevideo', 'longitud' => -56.1882, 'latitud' => -34.9058],
                'detalle'    => ['nro_habitaciones' => 2, 'nro_banios' => 1, 'nro_garage' => 0, 'superficie_total' => 75, 'pisos' => 1, 'precio' => 750, 'anio_construccion' => 2015, 'estado_construccion' => 'Usado', 'deposito' => 750, 'cant_meses_deposito' => 2, 'expensas' => 120, 'acepta_mascotas' => false],
                'amenidades' => ['Ascensor', 'Aire acondicionado'],
            ],
            [
                'agente_index' => 0,
                'propiedad' => ['titulo' => 'Casa colonial en Colonia del Sacramento', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Casa', 'tipo_operacion' => 'Venta', 'calificacion' => 4.8],
                'ubicacion'  => ['direccion' => 'Calle de los Suspiros 123', 'localidad' => 'Colonia del Sacramento', 'departamento' => 'Colonia', 'longitud' => -57.8462, 'latitud' => -34.4714],
                'detalle'    => ['nro_habitaciones' => 4, 'nro_banios' => 3, 'nro_garage' => 2, 'superficie_total' => 280, 'pisos' => 2, 'precio' => 350000, 'anio_construccion' => 1950, 'estado_construccion' => 'Usado', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => null, 'acepta_mascotas' => true],
                'amenidades' => ['Jardín', 'Parrillero', 'Garage cubierto', 'Terraza'],
            ],
            [
                'agente_index' => 0,
                'propiedad' => ['titulo' => 'Terreno en zona urbana de Paysandú', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Terreno', 'tipo_operacion' => 'Venta', 'calificacion' => null],
                'ubicacion'  => ['direccion' => 'Av. España 890', 'localidad' => 'Paysandú', 'departamento' => 'Paysandú', 'longitud' => -58.0756, 'latitud' => -32.3229],
                'detalle'    => ['nro_habitaciones' => 0, 'nro_banios' => 0, 'nro_garage' => 0, 'superficie_total' => 500, 'pisos' => 0, 'precio' => 45000, 'anio_construccion' => 1900, 'estado_construccion' => 'Nuevo', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => null, 'acepta_mascotas' => false],
                'amenidades' => [],
            ],
            // ---- Agente 1 ----
            [
                'agente_index' => 1,
                'propiedad' => ['titulo' => 'Local comercial en Ciudad de la Costa', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Local', 'tipo_operacion' => 'Alquiler', 'calificacion' => 3.8],
                'ubicacion'  => ['direccion' => 'Av. Giannattasio km 22', 'localidad' => 'Ciudad de la Costa', 'departamento' => 'Canelones', 'longitud' => -56.0412, 'latitud' => -34.8543],
                'detalle'    => ['nro_habitaciones' => 0, 'nro_banios' => 1, 'nro_garage' => 3, 'superficie_total' => 120, 'pisos' => 1, 'precio' => 1200, 'anio_construccion' => 2010, 'estado_construccion' => 'Usado', 'deposito' => 2400, 'cant_meses_deposito' => 2, 'expensas' => null, 'acepta_mascotas' => false],
                'amenidades' => ['Seguridad 24hs', 'Aire acondicionado'],
            ],
            [
                'agente_index' => 1,
                'propiedad' => ['titulo' => 'Apartamento de lujo en Carrasco', 'estado_propiedad' => 'Vendida', 'tipo_propiedad' => 'Apartamento', 'tipo_operacion' => 'Venta', 'calificacion' => 5.0],
                'ubicacion'  => ['direccion' => 'Av. Italia 5678, Piso 12', 'localidad' => 'Montevideo', 'departamento' => 'Montevideo', 'longitud' => -56.0345, 'latitud' => -34.8721],
                'detalle'    => ['nro_habitaciones' => 3, 'nro_banios' => 2, 'nro_garage' => 1, 'superficie_total' => 140, 'pisos' => 1, 'precio' => 320000, 'anio_construccion' => 2020, 'estado_construccion' => 'Nuevo', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => 280, 'acepta_mascotas' => true],
                'amenidades' => ['Piscina', 'Gimnasio', 'Seguridad 24hs', 'Salón de usos múltiples', 'Ascensor'],
            ],
            [
                'agente_index' => 1,
                'propiedad' => ['titulo' => 'Casa en alquiler en Salto', 'estado_propiedad' => 'Alquilada', 'tipo_propiedad' => 'Casa', 'tipo_operacion' => 'Alquiler', 'calificacion' => 3.5],
                'ubicacion'  => ['direccion' => 'Calle Uruguay 345', 'localidad' => 'Salto', 'departamento' => 'Salto', 'longitud' => -57.9629, 'latitud' => -31.3833],
                'detalle'    => ['nro_habitaciones' => 2, 'nro_banios' => 1, 'nro_garage' => 1, 'superficie_total' => 90, 'pisos' => 1, 'precio' => 550, 'anio_construccion' => 2000, 'estado_construccion' => 'Usado', 'deposito' => 550, 'cant_meses_deposito' => 1, 'expensas' => null, 'acepta_mascotas' => true],
                'amenidades' => ['Jardín', 'Parrillero'],
            ],
            // ---- Agente 2 ----
            [
                'agente_index' => 2,
                'propiedad' => ['titulo' => 'Oficina en centro empresarial', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Oficina', 'tipo_operacion' => 'Alquiler', 'calificacion' => 4.2],
                'ubicacion'  => ['direccion' => 'Rincón 487, Piso 4', 'localidad' => 'Montevideo', 'departamento' => 'Montevideo', 'longitud' => -56.1978, 'latitud' => -34.9055],
                'detalle'    => ['nro_habitaciones' => 0, 'nro_banios' => 2, 'nro_garage' => 0, 'superficie_total' => 95, 'pisos' => 1, 'precio' => 1800, 'anio_construccion' => 2018, 'estado_construccion' => 'Nuevo', 'deposito' => 3600, 'cant_meses_deposito' => 2, 'expensas' => 350, 'acepta_mascotas' => false],
                'amenidades' => ['Ascensor', 'Seguridad 24hs', 'Aire acondicionado'],
            ],
            [
                'agente_index' => 2,
                'propiedad' => ['titulo' => 'Casa frente al mar en Piriápolis', 'estado_propiedad' => 'Reservada', 'tipo_propiedad' => 'Casa', 'tipo_operacion' => 'Venta', 'calificacion' => 4.7],
                'ubicacion'  => ['direccion' => 'Rambla de los Argentinos 2100', 'localidad' => 'Piriápolis', 'departamento' => 'Maldonado', 'longitud' => -55.2773, 'latitud' => -34.8691],
                'detalle'    => ['nro_habitaciones' => 3, 'nro_banios' => 2, 'nro_garage' => 1, 'superficie_total' => 210, 'pisos' => 2, 'precio' => 290000, 'anio_construccion' => 2012, 'estado_construccion' => 'Usado', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => null, 'acepta_mascotas' => true],
                'amenidades' => ['Piscina', 'Terraza', 'Jardín', 'Parrillero'],
            ],
            [
                'agente_index' => 2,
                'propiedad' => ['titulo' => 'Apartamento en Punta Carretas', 'estado_propiedad' => 'Disponible', 'tipo_propiedad' => 'Apartamento', 'tipo_operacion' => 'Venta', 'calificacion' => 4.3],
                'ubicacion'  => ['direccion' => 'Ellauri 456, Piso 6', 'localidad' => 'Montevideo', 'departamento' => 'Montevideo', 'longitud' => -56.1512, 'latitud' => -34.9156],
                'detalle'    => ['nro_habitaciones' => 2, 'nro_banios' => 2, 'nro_garage' => 1, 'superficie_total' => 105, 'pisos' => 1, 'precio' => 175000, 'anio_construccion' => 2008, 'estado_construccion' => 'Usado', 'deposito' => null, 'cant_meses_deposito' => null, 'expensas' => 180, 'acepta_mascotas' => false],
                'amenidades' => ['Ascensor', 'Seguridad 24hs', 'Lavandería'],
            ],
        ];

        foreach ($propiedades as $data) {
            $agente = $agentes->get($data['agente_index'] % $agentes->count());

            $propiedad = Propiedad::create(array_merge($data['propiedad'], ['usuario_id' => $agente->id]));

            DetallePropiedad::create(array_merge($data['detalle'], ['propiedad_id' => $propiedad->id]));
            
            $deptoId = $depto($data['ubicacion']['departamento']);
            $locId   = $loc($data['ubicacion']['localidad'], $deptoId);
 
            if (!$deptoId) {
                $this->command->warn("Departamento no encontrado: {$data['ubicacion']['departamento']}");
            }
            if (!$locId) {
                $this->command->warn("Localidad no encontrada: {$data['ubicacion']['localidad']} ({$data['ubicacion']['departamento']})");
            }
             
            Ubicacion::create([
                'direccion'       => $data['ubicacion']['direccion'],
                'localidad_id'    => $locId,
                'departamento_id' => $deptoId,
                'latitud'         => $data['ubicacion']['latitud'],
                'longitud'        => $data['ubicacion']['longitud'],
                'propiedad_id'    => $propiedad->id,
            ]);

            if (!empty($data['amenidades'])) {
                $ids = Amenidad::whereIn('nombre', $data['amenidades'])->pluck('id');
                $propiedad->amenidades()->attach($ids);
            }

        }
    }
}
