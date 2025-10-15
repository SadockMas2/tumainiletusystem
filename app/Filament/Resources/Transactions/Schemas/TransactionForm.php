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
            'decaissement' => 'Decaissement',
            'credit' => 'Credit',
            'remboursement' => 'Remboursement',
        ])
                    ->required(),
                TextInput::make('source_type')
                    ->required(),
                TextInput::make('source_id')
                    ->required()
                    ->numeric(),
                TextInput::make('destination_type')
                    ->required(),
                TextInput::make('destination_id')
                    ->required()
                    ->numeric(),
                TextInput::make('membre_id')
                    ->numeric(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Select::make('devise')
                    ->options(['CDF' => 'C d f', 'USD' => 'U s d'])
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
