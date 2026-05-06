<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditActionGroup;
use Filament\Tables\Columns\ToggleColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable(),

                
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->getStateUsing(function ($record) {
                        if ($record->is_admin) {
                            return 'Administrador';
                        }

                        return $record->rol_usuario
                            ->pluck('nombre')
                            ->join(', ') ?: 'Sin rol';
                    })
                    ->badge()
                    ->color(
                        fn($state) =>
                        $state === 'Administrador' ? 'danger' : 'success'
                    ),

                ToggleColumn::make('activo'),
            ])

            ->filters([
                SelectFilter::make('tipo')
                    ->label('Filtrar por tipo')
                    ->options([
                        'admin' => 'Administrador',
                        'inmobiliaria' => 'Inmobiliaria',
                        'agente' => 'Agente',
                        'cliente' => 'Cliente',
                    ])
                    ->query(function ($query, $data) {
                        if (!$data['value']) return;

                        if ($data['value'] === 'admin') {
                            $query->where('is_admin', true);
                        } else {
                            $query->where('is_admin', false)
                                ->whereHas('rol_usuario', function ($q) use ($data) {
                                    $q->where('nombre', $data['value']);
                                });
                        }
                    }),
            ]);
    }
}
