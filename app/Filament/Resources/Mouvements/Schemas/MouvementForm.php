<?php

namespace App\Filament\Resources\Mouvements\Schemas;

use App\Models\Compte;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MouvementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Select::make('compte_id')
                ->label('Compte')
                ->options(Compte::all()->pluck('numero_compte', 'id'))
                ->required()
                ->reactive(),
            TextInput::make('nom_deposant')
                 ->label('Nom du déposant')
                 ->required(),

            Select::make('type')
                ->label('Type de mouvement')
                ->options([
                    'depot' => 'Dépôt',
                    'retrait' => 'Retrait',
                ])
                ->required(),

            TextInput::make('montant')
                ->label('Montant')
                ->numeric()
                ->required()
                ->rule(fn ($get) => $get('type') === 'retrait'
                    ? function ($attribute, $value, $fail) use ($get) {
                        $compte = Compte::find($get('compte_id'));
                        if ($compte && $value > $compte->solde) {
                            $fail('Le montant du retrait ne peut pas dépasser le solde du compte.');
                        }
                    }
                    : null
                ),

            TextInput::make('description')
                ->label('Description')
                ->nullable(),
            ]);
    }
}
