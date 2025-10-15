<?php

namespace App\Filament\Resources\CompteBancaires;

use App\Filament\Resources\CompteBancaires\Pages\CreateCompteBancaire;
use App\Filament\Resources\CompteBancaires\Pages\EditCompteBancaire;
use App\Filament\Resources\CompteBancaires\Pages\ListCompteBancaires;
use App\Filament\Resources\CompteBancaires\Schemas\CompteBancaireForm;
use App\Filament\Resources\CompteBancaires\Tables\CompteBancairesTable;
use App\Models\CompteBancaire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompteBancaireResource extends Resource
{
    protected static ?string $model = CompteBancaire::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CompteBancaireForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompteBancairesTable::configure($table);
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
            'index' => ListCompteBancaires::route('/'),
            'create' => CreateCompteBancaire::route('/create'),
            'edit' => EditCompteBancaire::route('/{record}/edit'),
        ];
    }
}
