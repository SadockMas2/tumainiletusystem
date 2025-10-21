<?php

namespace App\Filament\Resources\GroupeSolidaires\Schemas;

use App\Models\Client;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GroupeSolidaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_groupe')
                ->label('Numéro du groupe')
                ->disabled() // Empêche la modification
                ->dehydrated(false) // Évite de l’enregistrer depuis le form
                ->visible(fn ($record) => $record), // Visible seulement en édition
                
                TextInput::make('nom_groupe')
                    ->label('Nom du groupe')
                    ->required()
                    ->unique(ignoreRecord: true),


                    
                TextInput::make('numero_cycle')
                ->label('Numéro du cycle')
                ->required()
                ->numeric()
                ->minValue(1)
                ->placeholder('Ex: 1'),


                Textarea::make('adresse')
                    ->label('Adresse du groupe')
                    ->rows(2)
                    ->required(),

                DatePicker::make('date_debut_cycle')
                    ->label('Début du cycle')
                    ->required(),

                DatePicker::make('date_fin_cycle')
                    ->label('Fin du cycle')
                    ->required(),

              Select::make('membres')
                    ->label('Membres du groupe')
                    ->multiple()
                    ->minItems(1)
                    ->maxItems(15)
                    ->required()
                    ->options(Client::all()->mapWithKeys(function ($client) {
                        return [$client->id => $client->nom . ' ' . $client->postnom . ' ' . $client->prenom];
                    })->toArray())
                    ->searchable()
                    ->preload(),
            ]);
           
    }
}
