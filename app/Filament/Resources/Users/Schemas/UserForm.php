<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Rol;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
      return $schema
            ->components([
                TextInput::make('email')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->dehydrated(fn ($state) => filled($state)),

                Toggle::make('is_admin')
                    ->label('Administrador')
                    ->disabled(fn ($record) => $record && $record->id === auth()->id()),


                
                Select::make('rol_usuario')
                    ->label('Roles')
                    ->relationship('rol_usuario', 'nombre')
                    ->multiple() 
                    ->preload()
                    ->searchable()
                    ->visible(fn ($get) => !$get('is_admin'))
                    ->required(fn ($get) => !$get('is_admin')),

                Toggle::make('activo')
                    ->default(true),
            ]);
    }
}
