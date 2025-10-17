<?php

namespace App\Filament\Resources\Epargnes\Schemas;

use App\Models\Client;
use App\Models\GroupeSolidaire;
use App\Models\Cycle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;

class EpargneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Type d\'Épargne')
                    ->schema([
                        Select::make('type_epargne')
                            ->label('Type d\'Épargne')
                            ->options([
                                'individuel' => 'Épargne Individuelle',
                                'groupe_solidaire' => 'Épargne Groupe Solidaire',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Réinitialiser les champs lorsqu'on change le type
                                $set('client_id', null);
                                $set('groupe_solidaire_id', null);
                                $set('client_nom', null);
                                $set('cycle_id', null);
                                $set('montant', null);
                                $set('devise', null);
                            }),
                    ])
                    ->columns(1),

                Section::make('Sélection du Membre ou Groupe')
                    ->schema([
                        // Sélection du client (pour épargne individuelle)
                        Select::make('client_id')
                            ->label('Membre')
                            ->options(function () {
                                return Client::all()->mapWithKeys(function ($client) {
                                    $nomComplet = trim($client->nom . ' ' . $client->postnom . ' ' . $client->prenom);
                                    return [$client->id => $nomComplet ?: 'Inconnu'];
                                })->toArray();
                            })
                            ->required(fn ($get) => $get('type_epargne') === 'individuel')
                            ->visible(fn ($get) => $get('type_epargne') === 'individuel')
                            ->reactive()
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
                                        self::trouverCycle($set, $get, $selectedDevise, 'client_id', $state);
                                    }
                                }
                            }),

                        // Sélection du groupe (pour épargne de groupe)
                        Select::make('groupe_solidaire_id')
                            ->label('Groupe Solidaire')
                            ->options(function () {
                                return GroupeSolidaire::all()->mapWithKeys(function ($groupe) {
                                    return [$groupe->id => $groupe->nom_groupe ?: 'Groupe #' . $groupe->id];
                                })->toArray();
                            })
                            ->required(fn ($get) => $get('type_epargne') === 'groupe_solidaire')
                            ->visible(fn ($get) => $get('type_epargne') === 'groupe_solidaire')
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $set('client_nom', null);
                                $set('cycle_id', null);
                                $set('montant', null);
                                $set('devise', null);

                                $groupe = GroupeSolidaire::find($state);
                                if ($groupe) {
                                    $set('client_nom', $groupe->nom_groupe);

                                    // Si une devise a déjà été choisie, chercher le cycle correspondant
                                    $selectedDevise = $get('devise');
                                    if ($selectedDevise) {
                                        self::trouverCycle($set, $get, $selectedDevise, 'groupe_solidaire_id', $state);
                                    }
                                }
                            }),

                        TextInput::make('client_nom')
                            ->label('Nom Membre/Groupe')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Agent Collecteur')
                    ->schema([
                        Select::make('user_id')
                            ->label('Agent Collecteur')
                            ->options(\App\Models\User::all()->pluck('name', 'id'))
                            ->required()
                            ->afterStateUpdated(function ($state, $set) {
                                $user = \App\Models\User::find($state);
                                $set('agent_nom', $user ? $user->name : null);
                            }),

                        TextInput::make('agent_nom')
                            ->label('Nom de l\'agent')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Détails de l\'Épargne')
                    ->schema([
                        Select::make('devise')
                            ->label('Devise')
                            ->options(['USD' => 'USD', 'CDF' => 'CDF'])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $set('cycle_id', null);
                                $set('montant', null);

                                $typeEpargne = $get('type_epargne');
                                $clientId = $get('client_id');
                                $groupeId = $get('groupe_solidaire_id');

                                if ($typeEpargne === 'individuel' && $clientId) {
                                    self::trouverCycle($set, $get, $state, 'client_id', $clientId);
                                } elseif ($typeEpargne === 'groupe_solidaire' && $groupeId) {
                                    self::trouverCycle($set, $get, $state, 'groupe_solidaire_id', $groupeId);
                                }
                            }),

                        Select::make('cycle_id')
                            ->label('Cycle')
                            ->options(function ($get) {
                                $typeEpargne = $get('type_epargne');
                                $clientId = $get('client_id');
                                $groupeId = $get('groupe_solidaire_id');
                                $devise = $get('devise');

                                if (!$devise) return [];

                                if ($typeEpargne === 'individuel' && $clientId) {
                                    return Cycle::where('client_id', $clientId)
                                        ->where('devise', $devise)
                                        ->where('statut', 'ouvert')
                                        ->get()
                                        ->pluck('numero_cycle', 'id')
                                        ->toArray();
                                } elseif ($typeEpargne === 'groupe_solidaire' && $groupeId) {
                                    return Cycle::where('groupe_solidaire_id', $groupeId)
                                        ->where('devise', $devise)
                                        ->where('statut', 'ouvert')
                                        ->get()
                                        ->pluck('numero_cycle', 'id')
                                        ->toArray();
                                }

                                return [];
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($state) {
                                    $cycle = Cycle::find($state);
                                    if ($cycle) {
                                        $set('montant', $cycle->solde_initial);
                                        $set('devise', $cycle->devise);
                                    }
                                }
                            }),

                        TextInput::make('montant')
                            ->label('Montant de l\'épargne')
                            ->numeric()
                            ->required()
                            ->disabled(fn ($get) => !$get('cycle_id'))
                            ->dehydrated(),

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
                            ->label('Date d\'apport')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),

                // Champs cachés pour la logique
                Hidden::make('type_epargne')
                    ->default('individuel'),

                Hidden::make('premiere_mise')
                    ->default(false),
            ]);
    }

    /**
     * Méthode statique pour trouver un cycle ouvert selon le type
     */
    private static function trouverCycle($set, $get, $devise, $typeField, $id)
    {
        $cycle = Cycle::where($typeField, $id)
            ->where('devise', $devise)
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

            $typeLabel = $typeField === 'client_id' ? 'ce client' : 'ce groupe';

            Notification::make()
                ->title('Aucun cycle ouvert')
                ->body("{$typeLabel} n'a aucun cycle ouvert pour la devise {$devise}.")
                ->danger()
                ->send();
        }
    }
}