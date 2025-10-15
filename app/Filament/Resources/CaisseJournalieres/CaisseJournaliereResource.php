<?php

namespace App\Filament\Resources\CaisseJournalieres;

use App\Filament\Resources\CaisseJournalieres\Pages\CreateCaisseJournaliere;
use App\Filament\Resources\CaisseJournalieres\Pages\EditCaisseJournaliere;
use App\Filament\Resources\CaisseJournalieres\Pages\ListCaisseJournalieres;
use App\Filament\Resources\CaisseJournalieres\Schemas\CaisseJournaliereForm;
use App\Filament\Resources\CaisseJournalieres\Tables\CaisseJournalieresTable;
use App\Models\CaisseJournaliere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CaisseJournaliereResource extends Resource
{
    protected static ?string $model = CaisseJournaliere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CaisseJournaliereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CaisseJournalieresTable::configure($table);
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
            'index' => ListCaisseJournalieres::route('/'),
            'create' => CreateCaisseJournaliere::route('/create'),
            'edit' => EditCaisseJournaliere::route('/{record}/edit'),
        ];
    }
}
