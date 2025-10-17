<?php

namespace App\Filament\Resources\CaisseJournalieres\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CaisseJournaliereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom_caisse')
                ->label('Nom de la caisse')
                ->required() // ⚠️ obligatoire
                ->placeholder('Ex : Caisse Principale'),

                TextInput::make('devise')
                ->label('Devis')
                ->required() // ⚠️ obligatoire
                ->placeholder('Ex : USD'),

                TextInput::make('solde')
                ->label('Solde ')
                ->numeric()
                ->required()
                ->default(0),
            ]);
    }
}
