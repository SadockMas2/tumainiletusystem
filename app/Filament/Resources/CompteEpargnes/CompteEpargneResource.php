<?php

namespace App\Filament\Resources\CompteEpargnes;

use App\Filament\Resources\CompteEpargnes\Pages\CreateCompteEpargne;
use App\Filament\Resources\CompteEpargnes\Pages\EditCompteEpargne;
use App\Filament\Resources\CompteEpargnes\Pages\ListCompteEpargnes;
use App\Filament\Resources\CompteEpargnes\Schemas\CompteEpargneForm;
use App\Filament\Resources\CompteEpargnes\Tables\CompteEpargnesTable;
use App\Models\CompteEpargne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompteEpargneResource extends Resource
{
    protected static ?string $model = CompteEpargne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CompteEpargneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompteEpargnesTable::configure($table);
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
            'index' => ListCompteEpargnes::route('/'),
            'create' => CreateCompteEpargne::route('/create'),
            'edit' => EditCompteEpargne::route('/{record}/edit'),
        ];
    }
}
