<?php

namespace App\Filament\Resources\DispatchEpargnes;

// use App\Filament\Resources\DispatchEpargneResource\Pages\ListDispatchEpargnes;
use App\Filament\Resources\DispatchEpargneResource\Pages\ListDispatchEpargnes;
use App\Filament\Resources\DispatchEpargnes\Pages\CreateDispatchEpargne;
use App\Filament\Resources\DispatchEpargnes\Pages\EditDispatchEpargne;
// use App\Filament\Resources\DispatchEpargnes\Pages\ListDispatchEpargnes;
// use App\Filament\Resources\DispatchEpargnes\Schemas\DispatchEpargneForm;
// use App\Filament\Resources\DispatchEpargnes\Tables\DispatchEpargnesTable;
// // use App\Filament\Resources\DispatchEpargnes\Tables\DispatchEpargnesTable;
// use App\Filament\Resources\DispatchEpargnes\Schemas\DispatchEpargneForm;
// use App\Filament\Resources\DispatchEpargnes\Tables\DispatchEpargnesTable;
use App\Filament\Resources\DispatchEpargnes\Schemas\DispatchEpargneForm;
use App\Filament\Resources\DispatchEpargnes\Tables\DispatchEpargnesTable;
use App\Models\DispatchEpargne;
use App\Models\Epargne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DispatchEpargneResource extends Resource
{
  protected static ?string $model = Epargne::class;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-archive-box-arrow-down";

        protected static ?string $navigationLabel = 'Dispatch Epargnes';

     protected static string|UnitEnum|null $navigationGroup = '💰 EPARGNES';

    public static function form(Schema $schema): Schema
    {
        return DispatchEpargneForm::configure($schema);
    }
     public static function table(Table $table): Table
    {
        return DispatchEpargnesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDispatchEpargnes::route('/'),
        ];
    }
}