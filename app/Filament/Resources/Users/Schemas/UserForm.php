<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\FileUpload;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Nom complet')
                    ->required()
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('email')
                    ->label('Adresse email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                \Filament\Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required(fn ($operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->maxLength(255),

                Section::make('Photo de profil')
                    ->components([
                        \Filament\Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('users')
                            ->avatar()
                            ->maxSize(2048),
                    ]),
            
                Section::make('Rôles et Permissions')
                    ->components([
                        \Filament\Forms\Components\Select::make('roles')
                            ->label('Rôles')
                            ->multiple()
                            ->options(fn () => Role::where('guard_name', 'filament')->pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->loadingMessage('Chargement des rôles...')
                            ->noSearchResultsMessage('Aucun rôle trouvé'),

                        \Filament\Forms\Components\Select::make('permissions')
                            ->label('Permissions spécifiques')
                            ->multiple()
                            ->options(fn () => Permission::where('guard_name', 'filament')->pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->loadingMessage('Chargement des permissions...')
                            ->noSearchResultsMessage('Aucune permission trouvée')
                            ->helperText('Permissions directes (en plus des permissions des rôles)'),
                    ]),
            ]);
    }
}