<?php

namespace App\Filament\Resources\Coffres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CoffreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom'),
                Select::make('devise')
                    ->options(['CDF' => 'C d f', 'USD' => 'U s d'])
                    ->required(),
                TextInput::make('solde')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
