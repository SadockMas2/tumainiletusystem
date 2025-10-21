<?php

namespace App\Filament\Pages;

use App\Models\Epargne;
use App\Services\DispatchService;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Pages\Page;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class DispatchEpargnePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected  string $view = 'filament.pages.dispatch-epargne';
    protected static ?string $navigationLabel = 'Dispatch Épargnes';

    protected static string|UnitEnum|null $navigationGroup = '💰 EPARGNES';
    

     public $epargne_id;
    public $repartitions = [];
    public $montant_total = 0;
    public $devise;
    public $groupe_nom;

    public function mount(): void
    {
        $this->repartitions = [];
    }

    protected function getFormSchema(): array
    {
        $dispatchService = new DispatchService();
        $epargnes = $dispatchService->getEpargnesEnAttenteDispatch();

        return [
            Section::make('Sélection de l\'épargne à dispatcher')
                ->description('Choisissez une épargne de groupe à répartir entre les membres')
                ->schema([
                    Select::make('epargne_id')
                        ->label('Épargne de groupe')
                        ->options($epargnes)
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            if ($state) {
                                $this->chargerDetailsEpargne($state);
                            } else {
                                $this->resetForm();
                            }
                        })
                        ->required()
                        ->placeholder(count($epargnes) ? 'Sélectionnez une épargne' : 'Aucune épargne disponible')
                        ->columnSpanFull(),
                    
                    Grid::make(3)
                        ->schema([
                            TextInput::make('montant_total')
                                ->label('Montant total')
                                ->disabled()
                                ->dehydrated()
                                ->prefixIcon('heroicon-o-currency-dollar'),
                            
                            TextInput::make('devise')
                                ->label('Devise')
                                ->disabled()
                                ->dehydrated(),
                            
                            TextInput::make('groupe_nom')
                                ->label('Groupe solidaire')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                ]),
        ];
    }

    private function chargerDetailsEpargne($epargneId): void
    {
        $epargne = Epargne::with(['groupeSolidaire.membres'])->find($epargneId);
        
        if ($epargne) {
            $this->montant_total = $epargne->montant;
            $this->devise = $epargne->devise;
            $this->groupe_nom = $epargne->groupeSolidaire->nom_groupe;
            
            // Préparer les répartitions pour chaque membre
            $this->repartitions = [];
            foreach ($epargne->groupeSolidaire->membres as $membre) {
                $this->repartitions[] = [
                    'membre_id' => $membre->id,
                    'membre_nom' => trim($membre->nom . ' ' . $membre->postnom . ' ' . $membre->prenom),
                    'montant' => 0,
                ];
            }
        }
    }

    private function resetForm(): void
    {
        $this->epargne_id = null;
        $this->repartitions = [];
        $this->montant_total = 0;
        $this->devise = null;
        $this->groupe_nom = null;
    }

    public function getTotalRepartiProperty(): float
    {
        return collect($this->repartitions)->sum('montant');
    }

    public function getResteARepartirProperty(): float
    {
        return $this->montant_total - $this->totalReparti;
    }

    public function dispatcher(): void
    {
        $dispatchService = new DispatchService();
        
        try {
            // Validation supplémentaire
            if ($this->resteARepartir != 0) {
                throw new \Exception("Le montant total n'est pas entièrement réparti. Il reste " . number_format($this->resteARepartir, 2) . " {$this->devise} à répartir.");
            }

            if ($this->totalReparti <= 0) {
                throw new \Exception("Veuillez attribuer des montants aux membres avant de dispatcher.");
            }

            $dispatchService->dispatcherEpargneGroupe(
                $this->epargne_id, 
                $this->repartitions
            );
            
            Notification::make()
                ->title('Dispatch réussi !')
                ->body('L\'épargne a été dispatchée avec succès vers les comptes des membres.')
                ->success()
                ->send();
            
            $this->resetForm();
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erreur lors du dispatch')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getViewData(): array
    {
        return [
            'repartitions' => $this->repartitions,
            'totalReparti' => $this->totalReparti,
            'resteARepartir' => $this->resteARepartir,
            'montant_total' => $this->montant_total,
            'devise' => $this->devise,
            'groupe_nom' => $this->groupe_nom,
            'epargne_id' => $this->epargne_id,
        ];
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('dispatcher')
                ->label('Dispatcher l\'épargne')
                ->button()
                ->size('lg')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->action('dispatcher')
                ->hidden(fn () => !$this->epargne_id)
                ->disabled(fn () => $this->resteARepartir != 0 || $this->totalReparti <= 0),
        ];
    }

            public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('view_epargne');
    }
}
