<?php

namespace App\Filament\Resources\Mouvements\Tables;

use App\Models\User;
use App\Models\Mouvement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MouvementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_compte')->label('Compte')->sortable(),
                TextColumn::make('client_nom')->label('Client')->sortable(),
                TextColumn::make('nom_deposant')->label('Nom du déposant/retirant')->sortable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('montant')
                    ->label('Montant')
                    ->sortable()
                    ->money('USD'),
                TextColumn::make('solde_apres')
                    ->label('Solde après')
                    ->sortable()
                    ->money('USD'),
                TextColumn::make('operateur.name')
                    ->label('Opérateur')
                    ->sortable(),
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

            ->headerActions([
                Action::make('create_compte')
                    ->label('Depot / Retrait')
                    ->icon('heroicon-o-currency-dollar')
                    ->visible(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        return $user && $user->can('create_compte');
                    })
                    ->url(route('filament.admin.resources.mouvements.create')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                // Action pour imprimer le bordereau
                Action::make('imprimer')
                    ->label('Imprimer Bordereau')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Mouvement $record) => route('mouvement.bordereau', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}