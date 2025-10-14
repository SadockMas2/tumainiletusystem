<?php

namespace App\Filament\Resources\CompteTransitoires;

use App\Filament\Resources\CompteTransitoires\Pages\CreateCompteTransitoire;
use App\Filament\Resources\CompteTransitoires\Pages\EditCompteTransitoire;
use App\Filament\Resources\CompteTransitoires\Pages\ListCompteTransitoires;
use App\Filament\Resources\CompteTransitoires\Schemas\CompteTransitoireForm;
use App\Filament\Resources\CompteTransitoires\Tables\CompteTransitoiresTable;
use App\Models\CompteTransitoire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompteTransitoireResource extends Resource
{
    protected static ?string $model = CompteTransitoire::class;

 protected static string|BackedEnum|null $navigationIcon = "heroicon-o-arrows-right-left";

     protected static string|UnitEnum|null $navigationGroup = '💰 Gestion financière';

    public static function form(Schema $schema): Schema
    {
        return CompteTransitoireForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompteTransitoiresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompteTransitoires::route('/'),
            'create' => CreateCompteTransitoire::route('/create'),
            'edit' => EditCompteTransitoire::route('/{record}/edit'),
        ];
    }
}
