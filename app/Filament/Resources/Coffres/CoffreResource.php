<?php

namespace App\Filament\Resources\Coffres;

use App\Filament\Resources\Coffres\Pages\CreateCoffre;
use App\Filament\Resources\Coffres\Pages\EditCoffre;
use App\Filament\Resources\Coffres\Pages\ListCoffres;
use App\Filament\Resources\Coffres\Schemas\CoffreForm;
use App\Filament\Resources\Coffres\Tables\CoffresTable;
use App\Models\Coffre;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CoffreResource extends Resource
{
    protected static ?string $model = Coffre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CoffreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoffresTable::configure($table);
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
            'index' => ListCoffres::route('/'),
            'create' => CreateCoffre::route('/create'),
            'edit' => EditCoffre::route('/{record}/edit'),
        ];
    }
}
