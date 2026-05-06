<?php

namespace App\Filament\Resources\Propiedads\Pages;

use App\Filament\Resources\Propiedads\PropiedadResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ImagenPropiedad;
use App\Services\ImageUploadService;

class CreatePropiedad extends CreateRecord
{
    protected static string $resource = PropiedadResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $imagenes = $data['imagenes_upload'] ?? [];
        unset($data['imagenes_upload']);

        $propiedad = static::getModel()::create($data);

        if (!empty($imagenes)) {
            $uploader = new ImageUploadService();
            $esPrimera = true;

            foreach ($imagenes as $file) {
                $resultado  = $uploader->upload($file, 'litoral-hogar/propiedades');

                ImagenPropiedad::create([
                    'url'          => $resultado['secure_url'],
                    'public_id'    => $resultado['public_id'],
                    'propiedad_id' => $propiedad->id,
                    'usuario_id'   => auth()->user()?->getAuthIdentifier(),
                    'es_principal' => $esPrimera,
                    'orden'        => 1,
                ]);

                $esPrimera = false;
            }
        }

        return $propiedad;
    }
}
