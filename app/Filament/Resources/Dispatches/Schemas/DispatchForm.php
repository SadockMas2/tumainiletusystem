<?php

namespace App\Filament\Resources\Dispatches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DispatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Caissier')
                    ->relationship('caissier', 'name')
                    ->required(),
               Select::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'name')
                    ->required(),
                Select::make('devise')
                    ->options([
                        'USD' => 'USD',
                        'CDF' => 'CDF',
                    ])
                    ->required(),

               TextInput::make('montant_total')
                    ->numeric()
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Select::make('statut')
                    ->options(['effectue' => 'Effectue', 'partiel' => 'Partiel', 'echec' => 'Echec'])
                    ->default('effectue')
                    ->required(),
            ]);
    }
}
