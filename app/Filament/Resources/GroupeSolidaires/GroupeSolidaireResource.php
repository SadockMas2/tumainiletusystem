<?php

namespace App\Filament\Resources\GroupeSolidaires;

use App\Filament\Resources\GroupeSolidaires\Pages\CreateGroupeSolidaire;
use App\Filament\Resources\GroupeSolidaires\Pages\EditGroupeSolidaire;
use App\Filament\Resources\GroupeSolidaires\Pages\ListGroupeSolidaires;
use App\Filament\Resources\GroupeSolidaires\Schemas\GroupeSolidaireForm;
use App\Filament\Resources\GroupeSolidaires\Tables\GroupeSolidairesTable;
use App\Models\GroupeSolidaire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GroupeSolidaireResource extends Resource
{
    protected static ?string $model = GroupeSolidaire::class;

  
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Groupes Solidaires';
     protected static string|UnitEnum|null $navigationGroup =  '👨‍💼 GESTION DES MEMBRES';

        public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } 
    public static function form(Schema $schema): Schema
    {
        return GroupeSolidaireForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GroupeSolidairesTable::configure($table);
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
            'index' => ListGroupeSolidaires::route('/'),
            'create' => CreateGroupeSolidaire::route('/create'),
            'edit' => EditGroupeSolidaire::route('/{record}/edit'),
        ];
    }

     public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && $user->can('view_groupesolidaire');
    }

    
}
