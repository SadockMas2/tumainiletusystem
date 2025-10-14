<?php

namespace App\Filament\Resources\Epargnes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EpargnesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_nom')->label('Membre'),
                TextColumn::make('agent_nom')->label('Agent'),
                TextColumn::make('cycle.numero_cycle')->label('Cycle'),
                TextColumn::make('montant')->money('cdf', true),
                TextColumn::make('devise'),
                TextColumn::make('numero_compte_membre')->label('N° compte client'),
                TextColumn::make('solde_apres_membre')->label('Solde après dépôt')->money('cdf', true),
                TextColumn::make('date_apport')->dateTime(),
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
