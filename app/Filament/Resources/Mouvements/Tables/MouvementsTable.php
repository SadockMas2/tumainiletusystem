<?php

namespace App\Filament\Resources\Mouvements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MouvementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_compte')->label('Compte')->sortable(),
                TextColumn::make('client_nom')->label('Client')->sortable(),
                TextColumn::make('nom_deposant')->label('Nom du déposant')->sortable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('montant')->label('Montant')->sortable(),
                TextColumn::make('solde_apres')->label('Solde après')->sortable(),
                TextColumn::make('description')->label('Description')->toggleable(),
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
