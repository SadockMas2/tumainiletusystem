<?php

namespace App\Filament\Resources\Comptes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('client_id')
                    ->required()
                    ->numeric(),
                TextInput::make('numero_compte')
                    ->required(),
                Select::make('type_compte')
                    ->options(['courant' => 'Courant', 'épargne' => 'Épargne', 'autre' => 'Autre'])
                    ->default('courant')
                    ->required(),
                TextInput::make('solde')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('date_ouverture')
                    ->required(),
                Select::make('statut')
                    ->options(['actif' => 'Actif', 'inactif' => 'Inactif', 'ferme' => 'Ferme'])
                    ->default('actif')
                    ->required(),
                TextInput::make('devise')
                    ->required()
                    ->default('USD'),
            ]);
    }
}
