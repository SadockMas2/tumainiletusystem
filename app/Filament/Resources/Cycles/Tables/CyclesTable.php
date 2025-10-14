<?php

namespace App\Filament\Resources\Cycles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CyclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_nom')->label('Client')->sortable(),
                TextColumn::make('numero_cycle')->label('Numéro du cycle')->sortable(),
                TextColumn::make('devise')->label('Devise'),
                TextColumn::make('solde_initial')->label('Solde initial')->money('cdf', true),
                TextColumn::make('statut')->label('Statut'),
                TextColumn::make('date_debut')->date()->label('Début'),
                TextColumn::make('date_fin')->date()->label('Fin')->toggleable(),
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
