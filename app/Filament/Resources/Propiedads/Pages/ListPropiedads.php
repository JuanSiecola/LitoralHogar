<?php

namespace App\Filament\Resources\Propiedads\Pages;

use App\Filament\Resources\Propiedads\PropiedadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPropiedads extends ListRecords
{
    protected static string $resource = PropiedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
