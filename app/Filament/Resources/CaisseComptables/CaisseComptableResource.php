<?php

namespace App\Filament\Resources\CaisseComptables;

use App\Filament\Resources\CaisseComptables\Pages\CreateCaisseComptable;
use App\Filament\Resources\CaisseComptables\Pages\EditCaisseComptable;
use App\Filament\Resources\CaisseComptables\Pages\ListCaisseComptables;
use App\Filament\Resources\CaisseComptables\Schemas\CaisseComptableForm;
use App\Filament\Resources\CaisseComptables\Tables\CaisseComptablesTable;
use App\Models\CaisseComptable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CaisseComptableResource extends Resource
{
    protected static ?string $model = CaisseComptable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CaisseComptableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CaisseComptablesTable::configure($table);
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
            'index' => ListCaisseComptables::route('/'),
            'create' => CreateCaisseComptable::route('/create'),
            'edit' => EditCaisseComptable::route('/{record}/edit'),
        ];
    }
}
