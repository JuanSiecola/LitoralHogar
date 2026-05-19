<?php

namespace App\Actions\Propiedad;

use App\Models\Propiedad;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\DB;

class PropiedadCrearAction
{
    public function handle(array $data, int $usuarioId): Propiedad
    {
        $uploader = new ImageUploadService();
        $imagenesSubidas = [];
        foreach ($data['imagenes'] ?? [] as $archivo) {
            $imagenesSubidas[] = $uploader->upload($archivo, 'litoral-hogar/propiedades');
        }

        $imagenPrincipalIndex = (int) ($data['imagen_principal_index'] ?? 0);

        return DB::transaction(function () use ($data, $usuarioId, $imagenesSubidas, $imagenPrincipalIndex) {
            $propiedad = Propiedad::create([
                'titulo' => $data['titulo'],
                'tipo_propiedad' => $data['tipo_propiedad'],
                'tipo_operacion' => $data['tipo_operacion'],
                'estado_propiedad' => $data['estado_propiedad'],
                'usuario_id' => $usuarioId,
            ]);

            $propiedad->detalle_propiedad()->create([
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

            foreach ($imagenesSubidas as $orden => $resultado) {
                $propiedad->imagenes()->create([
                    'url' => $resultado['secure_url'],
                    'public_id' => $resultado['public_id'],
                    'orden' => $orden,
                    'es_principal' => $orden === $imagenPrincipalIndex,
                    'usuario_id' => $usuarioId,
                ]);
            }

            $propiedad->ubicacion()->create([
                'direccion' => $data['direccion'],
                'localidad' => $data['localidad'],
                'departamento' => $data['departamento'],
                'latitud' => $data['latitud'] ?? null,
                'longitud' => $data['longitud'] ?? null,
            ]);

            \Log::info('validatedData amenidades:', ['data' => $validatedData['amenidades'] ?? 'NULL']);

            $propiedad->amenidades()->sync($data['amenidades'] ?? []);

            return $propiedad;
        });
    }
}
