<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom complet')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Adresse email')
                    ->searchable(),

                ImageColumn::make('image')
                    ->label('Photo de profil'),

                TextColumn::make('roles.name')
                    ->label('Rôles')
                    ->badge()
                    ->separator(', ')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([])

            ->recordActions([
                EditAction::make()
                    ->visible(fn () => Auth::user()?->can('edit_user')),

                DeleteAction::make()
                    ->visible(fn () => Auth::user()?->can('delete_user')),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->can('delete_user')),
                ]),
            ]);
    }
}
