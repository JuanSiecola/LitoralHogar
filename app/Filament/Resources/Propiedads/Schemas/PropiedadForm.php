<?php

namespace App\Filament\Resources\Propiedads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PropiedadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->required(),
                Select::make('estado_propiedad')
                    ->options([
            'Disponible' => 'Disponible',
            'Vendida' => 'Vendida',
            'Alquilada' => 'Alquilada',
            'Reservada' => 'Reservada',
            'Pausada' => 'Pausada',
        ])
                    ->required(),
                Select::make('tipo_propiedad')
                    ->options([
            'Casa' => 'Casa',
            'Apartamento' => 'Apartamento',
            'Local' => 'Local',
            'Terreno' => 'Terreno',
            'Oficina' => 'Oficina',
        ])
                    ->required(),
                Select::make('tipo_operacion')
                    ->options(['Venta' => 'Venta', 'Alquiler' => 'Alquiler'])
                    ->required(),
                TextInput::make('calificacion')
                    ->numeric(),
                DateTimePicker::make('fecha_publicacion')
                    ->required(),
                Select::make('usuario_id')
                    ->relationship('usuario', 'id')
                    ->required(),
            ]);
    }
}
