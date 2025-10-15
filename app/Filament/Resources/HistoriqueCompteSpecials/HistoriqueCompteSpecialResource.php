<?php

namespace App\Filament\Resources\HistoriqueCompteSpecials;

use App\Filament\Resources\HistoriqueCompteSpecials\Pages\CreateHistoriqueCompteSpecial;
use App\Filament\Resources\HistoriqueCompteSpecials\Pages\EditHistoriqueCompteSpecial;
use App\Filament\Resources\HistoriqueCompteSpecials\Pages\ListHistoriqueCompteSpecials;
use App\Filament\Resources\HistoriqueCompteSpecials\Schemas\HistoriqueCompteSpecialForm;
use App\Filament\Resources\HistoriqueCompteSpecials\Tables\HistoriqueCompteSpecialsTable;
use App\Models\HistoriqueCompteSpecial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HistoriqueCompteSpecialResource extends Resource
{
    protected static ?string $model = HistoriqueCompteSpecial::class;

        protected static ?string $navigationLabel = 'Historique_compte TUMAINI';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|UnitEnum|null $navigationGroup = '💳 Gestion des comptes';

    public static function form(Schema $schema): Schema
    {
        return HistoriqueCompteSpecialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoriqueCompteSpecialsTable::configure($table);
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
            'index' => ListHistoriqueCompteSpecials::route('/'),
            'create' => CreateHistoriqueCompteSpecial::route('/create'),
            'edit' => EditHistoriqueCompteSpecial::route('/{record}/edit'),
        ];
    }
}
