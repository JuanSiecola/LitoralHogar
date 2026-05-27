<?php

namespace App\Actions\Propiedad;

use App\Models\Propiedad;
use Illuminate\Support\Facades\DB;
use App\Services\ImageUploadService;
class PropiedadDeleteAction
{
    public function handle(Propiedad $propiedad): void
    {
        DB::transaction(function () use ($propiedad) {
            $imageUploader = new ImageUploadService();

            foreach ($propiedad->imagenes as $imagen) {
                $imageUploader->delete($imagen->public_id);
            }
            
            $propiedad->detalle_propiedad()->delete();
            $propiedad->ubicacion()->delete();
            $propiedad->imagenes()->delete();
            $propiedad->amenidades()->detach();
            $propiedad->favoritos()->detach();
            $propiedad->delete();
        });
    }
}