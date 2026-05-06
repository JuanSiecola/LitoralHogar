<?php

namespace App\Filament\Resources\Propiedads\Pages;

use App\Filament\Resources\Propiedads\PropiedadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\ImagenPropiedad;
use App\Services\ImageUploadService;

class EditPropiedad extends EditRecord
{
    protected static string $resource = PropiedadResource::class;

     protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $imagenes = $data['imagenes_upload'] ?? [];
        unset($data['imagenes_upload']);

        $record->update($data);

        if (!empty($imagenes)) {
            $uploader = new ImageUploadService();
            $esPrimera = $record->imagenes()->count() === 0;

            foreach ($imagenes as $file) {
                $resultado = $uploader->upload($file, 'litoral-hogar/propiedades');

                ImagenPropiedad::create([
                    'url'          => $resultado['secure_url'],
                    'public_id'    => $resultado['public_id'],
                    'propiedad_id' => $record->id,
                    'usuario_id'   => auth()->user()?->getAuthIdentifier(),
                    'es_principal' => $esPrimera,
                    'orden'        => 1,
                ]);

                $esPrimera = false;
            }
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
