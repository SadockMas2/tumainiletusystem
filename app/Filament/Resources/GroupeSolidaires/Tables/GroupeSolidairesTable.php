<?php

namespace App\Filament\Resources\GroupeSolidaires\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class GroupeSolidairesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('numero_groupe')
                ->label('Numéro')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('nom_groupe')->label('Nom du groupe'),
            TextColumn::make('numero_cycle')
                    ->label('Cycle')
                    ->sortable(),

                TextColumn::make('adresse')->label('Adresse'),
                TextColumn::make('membres_count')
                    ->counts('membres')
                    ->label('Nombre de membres'),               
                TextColumn::make('date_debut_cycle')->label('Début'),
                TextColumn::make('date_fin_cycle')->label('Fin'),
                TextColumn::make('created_at')->label('Créé le')->date(),
            ])
        
            ->filters([
                //
            ])

             ->headerActions([
                Action::make('create_groupesolidaire')
                    ->label('Creer un groupe')
                    ->icon('heroicon-o-user-plus')
                    ->visible(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        return $user && $user->can('create_groupesolidaire');
                    })
                    ->url(route('filament.admin.resources.groupe-solidaires.create')), // ✅ Correct pour création
            ])
           
            ->recordActions([
                EditAction::make(),
                   Action::make('voir_membres')
                    ->label('Voir les membres')
                    ->button()
                    ->color('primary')
                    ->modalHeading(fn ($record) => "Membres du groupe : {$record->nom_groupe}")
                    ->modalContent(fn ($record) => view('filament.tables.group-members', [
                        'record' => $record,
                        'membres' => $record->membres,
                    ]))
                    ->modalCancelActionLabel('Fermer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
