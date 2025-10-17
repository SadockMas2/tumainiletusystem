<?php

namespace App\Filament\Resources\CompteBancaires\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompteBancairesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nom_banque')->label('Banque')->searchable()->sortable(),
                TextColumn::make('numero_compte')->label('Numéro de compte')->searchable(),
                TextColumn::make('devise')->label('Devise'),
                TextColumn::make('solde')->label('Solde')
                    ->money(fn ($record) => $record->devise)
                    ->sortable(),
                TextColumn::make('created_at')->label('Créé le')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                ->label('Modifier'),

                DeleteAction::make()
                ->label('Supprimer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
