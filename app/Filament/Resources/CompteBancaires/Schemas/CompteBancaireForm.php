<?php

namespace App\Filament\Resources\CompteBancaires\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompteBancaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                       TextInput::make('nom_banque')
                ->label('Nom de la banque')
                ->required()
                ->maxLength(255),

            TextInput::make('numero_compte')
                ->label('Numéro de compte')
                ->required()
                ->maxLength(50),

            Select::make('devise')
                ->label('Devise')
                ->options([
                    'CDF' => 'Franc Congolais (CDF)',
                    'USD' => 'Dollar Américain (USD)',
                ])
                ->required(),

            TextInput::make('solde')
                ->label('Solde actuel')
                ->numeric()
                ->default(0)
                ->required(),
            ]);
    }
}
