<?php

namespace App\Actions\Propiedad;

use App\Models\Propiedad;
use Illuminate\Support\Facades\DB;

class PropiedadEditAction
{
    public function handle(Propiedad $propiedad, array $data): Propiedad
    {
        return DB::transaction(function () use ($propiedad, $data) {
            $propiedad->update([
                'titulo'           => $data['titulo'],
                'tipo_propiedad'   => $data['tipo_propiedad'],
                'tipo_operacion'   => $data['tipo_operacion'],
                'estado_propiedad' => $data['estado_propiedad'],
            ]);

            $propiedad->detalle_propiedad->update([
                'nro_habitaciones'    => $data['nro_habitaciones'],
                'nro_banios'          => $data['nro_banios'],
                'nro_garage'          => $data['nro_garage'],
                'superficie_total'    => $data['superficie_total'],
                'pisos'               => $data['pisos'],
                'precio'              => $data['precio'],
                'anio_construccion'   => $data['anio_construccion'],
                'estado_construccion' => $data['estado_construccion'],
                'deposito'            => $data['deposito'] ?? null,
                'cant_meses_deposito' => $data['cant_meses_deposito'] ?? null,
                'expensas'            => $data['expensas'] ?? null,
                'acepta_mascotas'     => $data['acepta_mascotas'] ?? false,
            ]);

            $propiedad->ubicacion->update([
                'direccion'    => $data['direccion'],
                'localidad'    => $data['localidad'],
                'departamento' => $data['departamento'],
                'latitud'      => $data['latitud'] ?? null,
                'longitud'     => $data['longitud'] ?? null,
            ]);

            $propiedad->amenidades()->sync($data['amenidades'] ?? []);

            return $propiedad->fresh();
        });
    }
}
