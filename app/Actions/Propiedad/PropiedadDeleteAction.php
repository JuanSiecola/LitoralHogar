<?php

namespace App\Actions\Propiedad;

use App\Models\Propiedad;
use Illuminate\Support\Facades\DB;

class PropiedadDeleteAction
{
    public function handle(Propiedad $propiedad): void
    {
        DB::transaction(function () use ($propiedad) {
            $propiedad->detalle_propiedad()->delete();
            $propiedad->ubicacion()->delete();
            $propiedad->amenidades()->detach();
            $propiedad->delete();
        });
    }
}