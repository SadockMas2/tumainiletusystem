<?php

namespace App\Filament\Resources\Comptes\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Credit;
use Illuminate\Support\Facades\Auth;

class ComptesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_compte')->label('Compte'),
                TextColumn::make('numero_membre')->label('Numéro Membre'),
                TextColumn::make('client.nom')->label('Nom'),
                TextColumn::make('client.postnom')->label('Post'),
                TextColumn::make('client.prenom')->label('Prénom'),
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
               
                    ViewAction::make()
                        ->visible(function ($record) {
                            /** @var \App\Models\User $user */
                            $user = Auth::user();
                            return $user?->can('view', $record);
                        }),

                    EditAction::make()
                        ->visible(function ($record) {
                            /** @var \App\Models\User $user */
                            $user = Auth::user();
                            return $user?->can('update', $record);
                        }),

                    DeleteAction::make()
                        ->visible(function ($record) {
                            /** @var \App\Models\User $user */
                            $user = Auth::user();
                            return $user?->can('delete', $record);
                        }),
               

                // Bouton "Demander un crédit"
                Action::make('demande_credit')
                    ->label('Demander un crédit')
                    ->button()
                    ->color('primary')
                    ->icon('heroicon-o-credit-card')
                    ->action(function ($record, $data, $livewire) {
                        return redirect()->route('credits.create', ['compte_id' => $record->id]);
                    }),

                // Bouton "Payer un crédit"
                Action::make('payer_credit')
                    ->label('Payer un crédit')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-currency-dollar') 
                    ->action(function ($record, $data, $livewire) {
                        return redirect()->route('credits.payer', ['compte_id' => $record->id]);
                    }),

                // Bouton "Accorder un crédit" - Visible seulement s'il y a des demandes en attente
                Action::make('accorder_credit')
                    ->label('Accorder crédit')
                    ->button()
                    ->color('warning')
                    ->icon('heroicon-o-check-badge')
                    ->visible(function ($record) {
                        // Vérifier s'il y a des crédits en attente pour ce compte
                        return Credit::where('compte_id', $record->id)
                            ->where('statut_demande', 'en_attente')
                            ->exists();
                    })
                    ->action(function ($record, $data, $livewire) {
                        // Trouver le premier crédit en attente
                        $creditEnAttente = Credit::where('compte_id', $record->id)
                            ->where('statut_demande', 'en_attente')
                            ->first();
                        
                        if ($creditEnAttente) {
                            return redirect()->route('credits.accorder', ['credit_id' => $creditEnAttente->id]);
                        }
                        
                        // Fallback - rediriger vers les détails s'il n'y a pas de crédit en attente
                        return redirect()->route('comptes.details', ['compte_id' => $record->id]);
                    }),

                // Bouton "Voir détails"
           Action::make('voir_details')
                    ->label('Détails')
                    ->button()
                    ->color('secondary')
                    ->icon('heroicon-o-eye')
                    ->action(function ($record, $data, $livewire) {
                        return redirect()->route('comptes.details', ['compte_id' => $record->id]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}