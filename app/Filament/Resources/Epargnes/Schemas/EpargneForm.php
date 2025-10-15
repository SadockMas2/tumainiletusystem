<?php

namespace App\Filament\Resources\Epargnes\Schemas;

use App\Models\Client;
use App\Models\Cycle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;

class EpargneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sélection du client
                Select::make('client_id')
    ->label('Membre')
    ->options(function () {
        return Client::all()->mapWithKeys(function ($client) {
            $nomComplet = trim($client->nom . ' ' . $client->postnom . ' ' . $client->prenom);
            return [$client->id => $nomComplet ?: 'Inconnu'];
        })->toArray();
    })
    ->required()
    ->reactive() // déclenche la logique après sélection
    ->afterStateUpdated(function ($state, $set, $get) {
        $set('client_nom', null);
        $set('cycle_id', null);
        $set('montant', null);
        $set('devise', null);

        $client = Client::find($state);
        if ($client) {
            $set('client_nom', trim($client->nom . ' ' . $client->postnom . ' ' . $client->prenom));

            // Si une devise a déjà été choisie, chercher le cycle correspondant
            $selectedDevise = $get('devise');
            if ($selectedDevise) {
                $cycle = Cycle::where('client_id', $client->id)
                    ->where('devise', $selectedDevise)
                    ->where('statut', 'ouvert')
                    ->latest('id')
                    ->first();

                if ($cycle) {
                    $set('cycle_id', $cycle->id);
                    $set('montant', $cycle->solde_initial);
                    $set('devise', $cycle->devise);
                } else {
                    $set('cycle_id', null);
                    $set('montant', null);

                    Notification::make()
                        ->title('Aucun cycle ouvert')
                        ->body("Ce client n’a aucun cycle ouvert pour la devise {$selectedDevise}.")
                        ->danger()
                        ->send();
                }
            }
        }
    }),

                TextInput::make('client_nom')
                    ->label('Nom complet')
                    ->disabled()
                    ->dehydrated(), // ENVOIE malgré disabled

                // Agent collecteur
                Select::make('user_id')
                    ->label('Agent Collecteur')
                    ->options(\App\Models\User::all()->pluck('name', 'id'))
                    ->required()
                    ->afterStateUpdated(function ($state, $set) {
                        $user = \App\Models\User::find($state);
                        $set('agent_nom', $user ? $user->name : null);
                    }),

                TextInput::make('agent_nom')
                    ->label('Nom de l’agent')
                    ->disabled()
                    ->dehydrated(), 

                // Cycle
                Select::make('cycle_id')
                    ->label('Cycle')
                    ->options(Cycle::all()->pluck('numero_cycle', 'id'))
                    ->disabled()
                    ->required()
                    ->dehydrated(), 

                TextInput::make('montant')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                Select::make('devise')
                    ->label('Devise')
                    ->options(['USD' => 'USD', 'CDF' => 'CDF'])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('cycle_id', null);
                        $set('montant', null);

                        $clientId = $get('client_id');
                        if ($clientId) {
                            $cycle = Cycle::where('client_id', $clientId)
                                ->where('devise', $state)
                                ->where('statut', 'ouvert')
                                ->latest('id')
                                ->first();

                            if ($cycle) {
                                $set('cycle_id', $cycle->id);
                                $set('montant', $cycle->solde_initial);
                                $set('devise', $cycle->devise);
                            } else {
                                Notification::make()
                                    ->title('Aucun cycle ouvert')
                                    ->body("Ce client n’a aucun cycle ouvert pour la devise {$state}.")
                                    ->danger()
                                    ->send();
                            }
                        }
                    }),
                                Select::make('statut')
                    ->options([
                        'en_attente_dispatch' => 'En attente dispatch',
                        'en_attente_validation' => 'En attente validation',
                        'valide' => 'Validé',
                        'rejet' => 'Rejeté',
                    ])
                    ->default('en_attente_dispatch')
                    ->required(),

                DateTimePicker::make('date_apport')
                    ->label('Date d’apport')
                    ->required(),
            ]);
    }
}
