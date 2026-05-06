<?php

namespace App\Filament\Resources\Propiedads;

use App\Filament\Resources\Propiedads\Pages\CreatePropiedad;
use App\Filament\Resources\Propiedads\Pages\EditPropiedad;
use App\Filament\Resources\Propiedads\Pages\ListPropiedads;
use App\Filament\Resources\Propiedads\Schemas\PropiedadForm;
use App\Filament\Resources\Propiedads\Tables\PropiedadsTable;
use App\Models\Propiedad;
use BackedEnum;
use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class PropiedadResource extends Resource
{
    protected static ?string $model = Propiedad::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationIconIcon = 'heroicon-o-home';
    protected static ?string $modelLabel = 'Propiedad';
    protected static ?string $pluralModelLabel = 'Propiedades';


    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('titulo')
                ->required()
                ->maxLength(255),

            Select::make('estado_propiedad')
                ->options([
                    'Disponible' => 'Disponible',
                    'Vendida'    => 'Vendida',
                    'Alquilada'  => 'Alquilada',
                    'Reservada'  => 'Reservada',
                    'Pausada'    => 'Pausada',
                ])
                ->required(),

            Select::make('tipo_propiedad')
                ->options([
                    'Casa'        => 'Casa',
                    'Apartamento' => 'Apartamento',
                    'Local'       => 'Local',
                    'Terreno'     => 'Terreno',
                    'Oficina'     => 'Oficina',
                ])
                ->required(),

            Select::make('tipo_operacion')
                ->options([
                    'Venta'    => 'Venta',
                    'Alquiler' => 'Alquiler',
                ])
                ->required(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')->label('Título')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('estado_propiedad')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Disponible' => 'success',
                        'Vendida' => 'danger',
                        'Reservada' => 'warning',
                        'Alquilada' => 'primary',
                        'Pausada' => 'secondary',
                        default => 'secondary',
                    }),

                TextColumn::make('fecha_publicacion')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('tipo_propiedad')
                    ->sortable(),

                TextColumn::make('tipo_operacion')
                    ->badge(),

                TextColumn::make('calificacion')
                    ->sortable(),

                TextColumn::make('usuario.email')
                    ->label('Propietario')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('estado_propiedad')
                    ->options([
                        'Disponible' => 'Disponible',
                        'Vendida'    => 'Vendida',
                        'Alquilada'  => 'Alquilada',
                        'Reservada'  => 'Reservada',
                        'Pausada'    => 'Pausada',
                    ]),
                SelectFilter::make('tipo_propiedad')
                    ->options([
                        'Casa'        => 'Casa',
                        'Apartamento' => 'Apartamento',
                        'Local'       => 'Local',
                        'Terreno'     => 'Terreno',
                        'Oficina'     => 'Oficina',
                    ]),
                SelectFilter::make('tipo_operacion')
                    ->options([
                        'Venta'    => 'Venta',
                        'Alquiler' => 'Alquiler',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPropiedads::route('/'),
            'create' => CreatePropiedad::route('/create'),
            'edit' => EditPropiedad::route('/{record}/edit'),
        ];
    }
}
