<?php

namespace App\Filament\Resources\Comptes;

use App\Filament\Resources\Comptes\Pages\CreateCompte;
use App\Filament\Resources\Comptes\Pages\EditCompte;
use App\Filament\Resources\Comptes\Pages\ListComptes;
use App\Filament\Resources\Comptes\Tables\ComptesTable;
use App\Models\Compte;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class CompteResource extends Resource
{
    protected static ?string $model = Compte::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Comptes';
    protected static string|UnitEnum|null $navigationGroup = '👨‍💼 Gestion des membres';

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('status','=','processing')->count();
    // } 

    public static function table(Table $table): Table
    {
        return ComptesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComptes::route('/'),
            'create' => CreateCompte::route('/create'),
            'edit' => EditCompte::route('/{record}/edit'),

          
        ];
    }

    
}

