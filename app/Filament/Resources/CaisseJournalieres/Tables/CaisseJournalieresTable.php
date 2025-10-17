<?php

namespace App\Filament\Resources\CaisseJournalieres\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CaisseJournalieresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('nom_caisse')
                    ->label('Nom de la caisse')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('devise')
                    ->label('Devise'),

                TextColumn::make('solde')
                    ->label('Solde')
                    ->numeric()
                    ->sortable(),
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
