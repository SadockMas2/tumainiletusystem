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
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $client = Client::find($state);

                        if ($client) {
                            $set('client_nom', $client->nom . ' ' . $client->postnom . ' ' . $client->prenom);

                            // Cherche le cycle actif du client
                            $cycleOuvert = Cycle::where('client_id', $client->id)
                                ->where('statut', 'ouvert')
                                ->latest('id')
                                ->first();

                            if ($cycleOuvert) {
                                $set('cycle_id', $cycleOuvert->id);
                                $set('devise', $cycleOuvert->devise);
                                $set('montant', $cycleOuvert->solde_initial);

                                Notification::make()
                                    ->title('Cycle trouvé')
                                    ->body("Le client possède un cycle ouvert (#{$cycleOuvert->numero_cycle}).")
                                    ->success()
                                    ->send();
                            } else {
                                $set('cycle_id', null);
                                $set('devise', null);
                                $set('montant', null);

                                Notification::make()
                                    ->title('Aucun cycle ouvert')
                                    ->body("Ce client n’a aucun cycle actif. Veuillez créer un nouveau cycle avant d’enregistrer une épargne.")
                                    ->danger()
                                    ->send();
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
                    ->dehydrated(), // ENVOIE malgré disabled

                // Cycle
                Select::make('cycle_id')
                    ->label('Cycle')
                    ->options(Cycle::all()->pluck('numero_cycle', 'id'))
                    ->disabled()
                    ->required()
                    ->dehydrated(), // ENVOIE malgré disabled

                TextInput::make('montant')
                    ->numeric()
                    ->required(),

                Select::make('devise')
                    ->options(['USD' => 'USD', 'CDF' => 'CDF'])
                    ->disabled()
                    ->required()
                    ->dehydrated(), // ENVOIE malgré disabled

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
