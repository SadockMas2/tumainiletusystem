<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options([
                        'approvisionnement' => 'Approvisionnement',
                        'decaissement' => 'Décaissement',
                        'credit' => 'Crédit',
                        'remboursement' => 'Remboursement',
                    ])
                    ->required(),
                Select::make('source_type')
                    ->label('Source')
                    ->options([
                        'App\Models\CompteBancaire' => 'Compte Bancaire',
                        'App\Models\Coffre' => 'Coffre',
                        'App\Models\CaisseComptable' => 'Caisse Comptable',
                        'App\Models\CaisseJournaliere' => 'Caisse Journalière',
                    ])
                    ->required(),

                // TextInput::make('source_id')
                //     ->label('ID de la source')
                //     ->required(),

                select::make('destination_type')
                    ->label('Destination')
                    ->options([
                        'App\Models\CompteBancaire' => 'Compte Bancaire',
                        'App\Models\Coffre' => 'Coffre',
                        'App\Models\CaisseComptable' => 'Caisse Comptable',
                        'App\Models\CaisseJournaliere' => 'Caisse Journalière',
                        'App\Models\Client' => 'Client',
                    ])
                    ->required(),

                // TextInput::make('destination_id')
                //     ->label('ID de la destination')
                //     ->required(),

                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'nom') // si tu as une relation client
                    ->searchable()
                    ->visible(fn (callable $get) => in_array($get('type'), ['credit', 'remboursement'])),

                TextInput::make('montant')
                    ->numeric()
                    ->required(),

                Select::make('devise')
                    ->options(['CDF' => 'CDF', 'USD' => 'USD'])
                    ->required(),

                Textarea::make('description'),
            ]);
    }
}
