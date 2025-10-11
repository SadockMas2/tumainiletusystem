<?php

namespace App\Filament\Resources\TypeComptes;

use App\Filament\Resources\TypeComptes\Pages\CreateTypeCompte;
use App\Filament\Resources\TypeComptes\Pages\EditTypeCompte;
use App\Filament\Resources\TypeComptes\Pages\ListTypeComptes;
use App\Filament\Resources\TypeComptes\Schemas\TypeCompteForm;
use App\Filament\Resources\TypeComptes\Tables\TypeComptesTable;
use App\Filament\Traits\HasRoleBasedAccess;
use App\Models\TypeCompte;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TypeCompteResource extends Resource
{
        use HasRoleBasedAccess;
    protected static ?string $model = TypeCompte::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } 

    public static function form(Schema $schema): Schema
    {
                return $schema
               ->schema([
                TextInput::make('designation')
                    ->label('Désignation')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return TypeComptesTable::configure($table);
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
            'index' => ListTypeComptes::route('/'),
            'create' => CreateTypeCompte::route('/create'),
            'edit' => EditTypeCompte::route('/{record}/edit'),
        ];
    }

      public static function afterCreate($record, array $data): void
        {
            if (!empty($data['roles'])) {
                $record->syncRoles($data['roles']);
            }

            if (!empty($data['permissions'])) {
                $record->syncPermissions($data['permissions']);
            }
        }

         public static function afterSave($record, array $data): void
        {
            if (!empty($data['roles'])) {
                $record->syncRoles($data['roles']);
            }

            if (!empty($data['permissions'])) {
                $record->syncPermissions($data['permissions']);
            }
        }
}
