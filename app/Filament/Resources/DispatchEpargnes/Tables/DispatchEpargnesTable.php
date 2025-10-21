<?php

namespace App\Filament\Resources\DispatchEpargnes\Tables;

use App\Models\Client;
use App\Models\Compte;
use App\Models\CompteTransitoire;
use App\Models\Epargne;
use App\Models\GroupeSolidaire;

use App\Models\Mouvement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use illuminate\Support\Facades\Auth;    


class DispatchEpargnesTable
{
    public static function configure(Table $table): Table
    {
        return $table
         ->query(function () {
                return Auth::user()->Dispatch()->getQuery();
            })
            ->columns([
                TextColumn::make('groupeSolidaire.nom_groupe')
                    ->label('Groupe')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('client_nom')
                    ->label('Nom du Groupe')
                    ->searchable(),
                    
                TextColumn::make('montant')
                    ->label('Montant Collecté')
                    ->money(fn ($record) => $record->devise)
                    ->sortable(),
                    
               TextColumn::make('devise')
                    ->label('Devise')
                    ->badge(),
                    
                TextColumn::make('agent_nom')
                    ->label('Agent Collecteur')
                    ->searchable(),
                    
                TextColumn::make('date_apport')
                    ->label('Date Collecte')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->colors([
                        'warning' => 'en_attente_dispatch',
                        'success' => 'valide',
                    ]),
            ])
            ->filters([
                SelectFilter::make('groupe_solidaire_id')
                    ->label('Groupe Solidaire')
                    ->options(GroupeSolidaire::all()->pluck('nom_groupe', 'id')),
                    
                Filter::make('date_collecte')
                    ->schema([
                        DatePicker::make('date_collecte')
                            ->label('Date de Collecte')
                            ->default(today()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date_collecte'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', $date),
                        );
                    }),
            ])
            ->recordActions([
                Action::make('dispatcher')
                    ->label('Dispatcher')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->schema(function (Epargne $record) {
                        $groupe = $record->groupeSolidaire;
                        $membres = $groupe ? $groupe->membres : collect();
                        
                        return [
                            Hidden::make('epargne_id')
                                ->default($record->id),
                                
                            TextInput::make('groupe_nom')
                                ->label('Groupe')
                                ->default($groupe->nom_groupe ?? '')
                                ->disabled(),
                                
                            TextInput::make('montant_total')
                                ->label('Montant à Dispatcher')
                                ->default($record->montant)
                                ->disabled(),
                                
                            TextInput::make('devise')
                                ->label('Devise')
                                ->default($record->devise)
                                ->disabled(),
                                
                            Repeater::make('repartition')
                                ->schema([
                                    Select::make('membre_id')
                                        ->label('Membre')
                                        ->options($membres->pluck('nom_complet', 'id')->toArray())
                                        ->required()
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $client = Client::find($state);
                                                if ($client) {
                                                    $set('membre_nom', $client->nom_complet);
                                                }
                                            }
                                        }),
                                        
                                    TextInput::make('membre_nom')
                                        ->label('Nom')
                                        ->disabled(),
                                        
                                    TextInput::make('montant')
                                        ->label('Montant')
                                        ->numeric()
                                        ->required()
                                        ->minValue(0)
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $get, callable $set) {
                                            self::calculerTotalAction($get, $set);
                                        }),
                                ])
                                ->columns(3)
                                ->required()
                                ->minItems(1),
                                
                            TextInput::make('total_reparti')
                                ->label('Total Réparti')
                                ->numeric()
                                ->disabled(),
                                
                            TextInput::make('reste_a_repartir')
                                ->label('Reste à Répartir')
                                ->numeric()
                                ->disabled(),
                        ];
                    })
                    ->action(function (array $data): void {
                        self::processDispatch($data);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Dispatcher l\'Épargne du Groupe')
                    ->modalDescription('Répartir le montant collecté entre les membres du groupe.')
                    ->modalSubmitActionLabel('Confirmer le Dispatch'),
            ])
           ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    /**
     * Calculer le total dans l'action
     */
    private static function calculerTotalAction(callable $get, callable $set): void
    {
        $repartitions = $get('../../repartition') ?? [];
        $total = collect($repartitions)->sum('montant');
        $montantTotal = $get('../../montant_total') ?? 0;
        
        $set('../../total_reparti', $total);
        $set('../../reste_a_repartir', $montantTotal - $total);
    }

    /**
     * Traiter le dispatch
     */
    private static function processDispatch(array $data): void
    {
        DB::transaction(function () use ($data) {
            $epargne = Epargne::findOrFail($data['epargne_id']);
            $montantTotal = $epargne->montant;
            $totalReparti = collect($data['repartition'])->sum('montant');
            
            // Vérifier que le total réparti correspond au montant total
            if (abs($totalReparti - $montantTotal) > 0.01) {
                Notification::make()
                    ->title('Erreur de Répartition')
                    ->body("Le total réparti ({$totalReparti}) ne correspond pas au montant collecté ({$montantTotal}).")
                    ->danger()
                    ->send();
                return;
            }
            
            // Débiter le compte transitoire de l'agent
            $compteTransitoire = CompteTransitoire::where('user_id', $epargne->user_id)
                ->where('devise', $epargne->devise)
                ->first();
                
            if (!$compteTransitoire || $compteTransitoire->solde < $montantTotal) {
                Notification::make()
                    ->title('Solde Transitoire Insuffisant')
                    ->body("L'agent ne dispose pas de suffisamment de fonds dans son compte transitoire.")
                    ->danger()
                    ->send();
                return;
            }
            
            $compteTransitoire->solde -= $montantTotal;
            $compteTransitoire->save();
            
            // Créditer les comptes des membres
            foreach ($data['repartition'] as $repartition) {
                $membreId = $repartition['membre_id'];
                $montantMembre = $repartition['montant'];
                
                if ($montantMembre <= 0) continue;
                
                // Trouver ou créer le compte du membre
                $compteMembre = Compte::firstOrCreate(
                    [
                        'client_id' => $membreId,
                        'devise' => $epargne->devise,
                        'type_compte' => 'individuel'
                    ],
                    [
                        'solde' => 0,
                        'numero_compte' => 'C' . str_pad($membreId, 6, '0', STR_PAD_LEFT),
                        'nom' => Client::find($membreId)->nom,
                        'postnom' => Client::find($membreId)->postnom,
                        'prenom' => Client::find($membreId)->prenom,
                        'statut' => 'actif'
                    ]
                );
                
                // Créditer le compte du membre
                $compteMembre->solde += $montantMembre;
                $compteMembre->save();
                
                // Créer un mouvement pour le compte du membre
                Mouvement::create([
                    'compte_id' => $compteMembre->id,
                    'numero_compte' => $compteMembre->numero_compte,
                    'client_nom' => $compteMembre->nom . ' ' . $compteMembre->postnom . ' ' . $compteMembre->prenom,
                    'nom_deposant' => $epargne->agent_nom,
                    'type' => 'depot',
                    'montant' => $montantMembre,
                    'solde_apres' => $compteMembre->solde,
                    'description' => 'Dispatch épargne groupe: ' . $epargne->groupeSolidaire->nom_groupe,
                ]);
            }
            
            // Marquer l'épargne comme validée
            $epargne->statut = 'valide';
            $epargne->save();
            
            Notification::make()
                ->title('Dispatch Réussi')
                ->body("Le montant de {$montantTotal} {$epargne->devise} a été réparti entre les membres du groupe.")
                ->success()
                ->send();
        });
    }
}