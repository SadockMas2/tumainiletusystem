<?php

namespace App\Filament\Resources\Epargnes\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class EpargnesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_compte_membre')
                    ->label('N° compte')
                    ->searchable(),

                TextColumn::make('client_nom')
                    ->label('Membre/Groupe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type_epargne')
                    ->label('Type')
                    ->colors([
                        'success' => 'individuel',
                        'warning' => 'groupe_solidaire',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'individuel' ? 'Individuel' : 'Groupe'),

                TextColumn::make('agent_nom')
                    ->label('Agent')
                    ->searchable(),

                TextColumn::make('cycle.numero_cycle')
                    ->label('Cycle')
                    ->sortable(),

                TextColumn::make('montant')
                    ->money(fn ($record) => $record->devise)
                    ->sortable(),

                TextColumn::make('solde_apres_membre')
                    ->label('Solde après dépôt')
                    ->money(fn ($record) => $record->devise)
                    ->sortable(),

                TextColumn::make('devise')
                    ->colors([
                        'primary' => 'USD',
                        'success' => 'CDF',
                    ]),

                TextColumn::make('date_apport')
                    ->label('Date dépôt')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('statut')
                    ->colors([
                        'warning' => 'en_attente_dispatch',
                        'info' => 'en_attente_validation',
                        'success' => 'valide',
                        'danger' => 'rejet',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'en_attente_dispatch' => 'En attente dispatch',
                        'en_attente_validation' => 'En attente validation',
                        'valide' => 'Validé',
                        'rejet' => 'Rejeté',
                        default => $state
                    }),

                // SUPPRIMER la colonne premiere_mise

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_epargne')
                    ->label('Type d\'Épargne')
                    ->options([
                        'individuel' => 'Individuelle',
                        'groupe_solidaire' => 'Groupe Solidaire',
                    ]),

                Tables\Filters\SelectFilter::make('devise')
                    ->label('Devise')
                    ->options([
                        'USD' => 'USD',
                        'CDF' => 'CDF',
                    ]),

                Tables\Filters\SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente_dispatch' => 'En attente dispatch',
                        'en_attente_validation' => 'En attente validation',
                        'valide' => 'Validé',
                        'rejet' => 'Rejeté',
                    ]),

                // SUPPRIMER le filtre premiere_mise
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}