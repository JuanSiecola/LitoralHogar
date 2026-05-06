<?php

namespace App\Filament\Resources\Propiedads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropiedadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->searchable(),
                TextColumn::make('estado_propiedad')
                    ->badge(),
                TextColumn::make('tipo_propiedad')
                    ->badge(),
                TextColumn::make('tipo_operacion')
                    ->badge(),
                TextColumn::make('calificacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_publicacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('usuario.id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
