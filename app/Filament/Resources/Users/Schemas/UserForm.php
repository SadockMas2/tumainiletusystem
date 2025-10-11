<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom complet')
                    ->required(),

                TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),

                FileUpload::make('image')
                    ->image()
                    ->required(),
            

                Select::make('roles')
                    ->label('Rôles')
                    ->multiple()
                    ->relationship('roles', 'name') 
                    ->preload()
                    ->required(),

                Select::make('permissions')
                    ->label('Permissions spécifiques')
                    ->multiple()
                    ->options(Permission::all()->pluck('name', 'name'))
                    ->preload(),
            ]);
    }
}
