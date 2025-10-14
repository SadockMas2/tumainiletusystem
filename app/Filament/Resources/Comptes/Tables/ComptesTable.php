<?php

namespace App\Filament\Resources\Comptes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComptesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_compte')->label('Compte'),
                TextColumn::make('numero_membre')->label('Numéro Membre'),
                TextColumn::make('client.nom')->label('Nom '),
                TextColumn::make('client.postnom')->label('Post '),
                TextColumn::make('client.prenom')->label('prenom '),
                TextColumn::make('devise')->label('Devise'),
                TextColumn::make('solde')->label('Solde'),
                TextColumn::make('statut')->label('Statut'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
