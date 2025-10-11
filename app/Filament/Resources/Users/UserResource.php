<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Traits\HasRoleBasedAccess;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
         use HasRoleBasedAccess;
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Agents';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';
    protected static string|UnitEnum|null $navigationGroup = '👨‍💼 Ressources Humaines';
    
      public static function shouldRegisterNavigation(): bool
    {
        return static::checkAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Hash du mot de passe si besoin
        $data['password'] = bcrypt($data['password']);
        return $data;
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

        // Méthode pour synchroniser les rôles et permissions après création/mise à jour
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
