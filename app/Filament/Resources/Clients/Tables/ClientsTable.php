<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Models\TypeCompte;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_membre')
                    ->label('Numéro membre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nom')->searchable(),
                TextColumn::make('postnom')->searchable(),
                TextColumn::make('prenom')->searchable(),
                TextColumn::make('date_naissance')->date()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('telephone')->searchable(),
                TextColumn::make('adresse')->searchable(),
                TextColumn::make('activites')->searchable(),
                TextColumn::make('ville')->searchable(),
                TextColumn::make('pays')->searchable(),
                TextColumn::make('code_postal')->searchable(),
                TextColumn::make('id_createur')->numeric()->sortable(),
                TextColumn::make('status')->searchable(),
                TextColumn::make('identifiant_national')->searchable(),
                TextColumn::make('type_client')->searchable(),
                TextColumn::make('etat_civil')->searchable(),
                TextColumn::make('type_compte')
                    ->label('Type de compte')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('Créer un client')
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = Auth::user();
                        // Vérifier la permission de création
                        return $user?->can('create', \App\Models\Client::class); // Remplacez par votre modèle Client
                    }),
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
            ])
          ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}