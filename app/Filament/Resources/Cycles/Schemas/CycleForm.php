<?php

namespace App\Filament\Resources\Cycles\Schemas;

use App\Models\Client;
use App\Models\Cycle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('client_id')
                ->label('Client')
                ->options(function () {
                    return Client::all()->mapWithKeys(function ($client) {
                        $nomComplet = trim($client->nom . ' ' . $client->postnom . ' ' . $client->prenom);
                        return [$client->id => $nomComplet ?: 'Inconnu'];
                    })->toArray();
                })
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    $client = Client::find($state);
                    if ($client) {
                        $set('client_nom', $client->nom . ' ' . $client->postnom . ' ' . $client->prenom);
                        $dernierCycle = Cycle::where('client_id', $state)->latest('numero_cycle')->first();
                        $set('numero_cycle', $dernierCycle ? $dernierCycle->numero_cycle + 1 : 1);
                    }
                }),


                TextInput::make('client_nom')->label('Nom complet')->disabled(),
                TextInput::make('numero_cycle')->label('Numéro du cycle')->disabled(),
                TextInput::make('solde_initial')->label('Solde initial')->numeric()->required(),
                Select::make('devise')->options(['USD' => 'USD', 'CDF' => 'CDF'])->required(),
                DatePicker::make('date_debut')->required(),
                DatePicker::make('date_fin'),
                Select::make('statut')->options(['ouvert' => 'Ouvert', 'clôturé' => 'Clôturé'])->default('ouvert')->required(),
            ]);
    }
}
