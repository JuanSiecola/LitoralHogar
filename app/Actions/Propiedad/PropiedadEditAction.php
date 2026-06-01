<?php

namespace App\Actions\Propiedad;

use App\Models\Propiedad;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\DB;

class PropiedadEditAction
{
    public function handle(Propiedad $propiedad, array $data): Propiedad
    {
        $uploader = new ImageUploadService();

        return DB::transaction(function () use ($propiedad, $data, $uploader) {
            $propiedad->update([
                'titulo' => $data['titulo'],
                'tipo_propiedad' => $data['tipo_propiedad'],
                'tipo_operacion' => $data['tipo_operacion'],
                'estado_propiedad' => $data['estado_propiedad'],
            ]);

            $propiedad->detalle_propiedad->update([
                'nro_habitaciones' => $data['nro_habitaciones'],
                'nro_banios' => $data['nro_banios'],
                'nro_garage' => $data['nro_garage'],
                'superficie_total' => $data['superficie_total'],
                'pisos' => $data['pisos'],
                'precio' => $data['precio'],
                'anio_construccion' => $data['anio_construccion'],
                'estado_construccion' => $data['estado_construccion'],
                'deposito' => $data['deposito'] ?? null,
                'cant_meses_deposito' => $data['cant_meses_deposito'] ?? null,
                'expensas' => $data['expensas'] ?? null,
                'acepta_mascotas' => $data['acepta_mascotas'] ?? false,
            ]);

            $propiedad->ubicacion->update([
                'direccion'        => $data['ubicacion']['direccion'],
                'departamento_id'  => $data['ubicacion']['departamento_id'],
                'localidad_id'     => $data['ubicacion']['localidad_id'],
                'latitud'          => $data['ubicacion']['latitud'] ?? null,
                'longitud'         => $data['ubicacion']['longitud'] ?? null,
            ]);

            $propiedad->amenidades()->sync($data['amenidades'] ?? []);

            // Eliminar imágenes marcadas
            if (!empty($data['imagenes_a_eliminar'])) {
                $propiedad->imagenes()
                    ->whereIn('id', $data['imagenes_a_eliminar'])
                    ->get()
                    ->each(function ($imagen) use ($uploader) {
                        $uploader->delete($imagen->public_id);
                        $imagen->delete();
                    });
            }

            // Subir imágenes nuevas
            if (!empty($data['imagenes'])) {
                $imagenPrincipalIndex = (int) ($data['imagen_principal_index'] ?? 0);
                $ordenActual = $propiedad->imagenes()->max('orden') ?? -1;
                $propiedad->imagenes()->update(['es_principal' => false]);

                foreach ($data['imagenes'] as $indice => $archivo) {
                    $resultado = $uploader->upload($archivo, 'litoral-hogar/propiedades', 'propiedad');
                    $propiedad->imagenes()->create([
                        'url' => $resultado['secure_url'],
                        'public_id' => $resultado['public_id'],
                        'orden' => ++$ordenActual,
                        'es_principal' => $indice === $imagenPrincipalIndex,
                        'usuario_id' => $propiedad->usuario_id,
                    ]);
                }
            }

            return $propiedad->fresh();
        });
    }
}