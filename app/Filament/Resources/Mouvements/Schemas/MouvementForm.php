<?php

namespace App\Filament\Resources\Mouvements\Schemas;

use App\Models\Compte;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Hidden;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MouvementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informations du mouvement')
                    ->schema([
                        Select::make('compte_id')
                            ->label('Compte')
                            ->options(Compte::all()->pluck('numero_compte', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $compte = Compte::find($state);
                                    if ($compte) {
                                        $set('client_nom', $compte->type_compte === 'groupe_solidaire' 
                                            ? $compte->nom . ' (Groupe)'
                                            : $compte->nom . ' ' . $compte->postnom . ' ' . $compte->prenom
                                        );
                                    }
                                }
                            }),

                        TextInput::make('client_nom')
                            ->label('Nom du client')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('type')
                            ->label('Type de mouvement')
                            ->options([
                                'depot' => 'Dépôt',
                                'retrait' => 'Retrait',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state === 'retrait') {
                                    $set('nom_deposant', 'Retrait');
                                }
                            }),

                         TextInput::make('nom_deposant')
                            ->label('Nom du déposant/retirant')
                            ->required()
                            ->placeholder(fn ($get) => $get('type') === 'depot' ? 'Nom du déposant' : 'Nom du retirant'),

                        TextInput::make('montant')
                            ->label('Montant')
                            ->numeric()
                            ->required()
                            ->rules([
                                fn ($get) => $get('type') === 'retrait'
                                    ? function ($attribute, $value, $fail) use ($get) {
                                        $compte = Compte::find($get('compte_id'));
                                        if ($compte && $value > $compte->solde) {
                                            $fail('Le montant du retrait ne peut pas dépasser le solde du compte.');
                                        }
                                    }
                                    : null
                            ]),

                        TextInput::make('description')
                            ->label('Description')
                            ->nullable(),

                        // Champ caché pour l'opérateur
                        Hidden::make('operateur_id')
                         ->default(fn () => Auth::id()), 
                    ]),
            ]);
    }
}