<?php

namespace App\Filament\Resources\Cycles\Pages;

use App\Filament\Resources\Cycles\CycleResource;
use App\Models\CompteSpecial;
use App\Models\Cycle;
use App\Models\HistoriqueCompteSpecial;
use Filament\Resources\Pages\CreateRecord;

class CreateCycle extends CreateRecord
{
    protected static string $resource = CycleResource::class;

    protected function afterCreate(): void
    {
        // 🔹 Récupère le cycle créé
        $cycle = $this->record;
        $montant = $cycle->solde_initial;

        // 🔹 Mettre à jour ou créer le compte spécial selon la devise
        $compte = CompteSpecial::firstOrCreate(
            ['devise' => $cycle->devise], // condition : devise
            ['solde' => 0]                // valeur par défaut
        );

        // 🔹 Ajouter le montant au compte
        $compte->increment('solde', $montant);

        // 🔹 Ajouter un enregistrement dans l’historique
        HistoriqueCompteSpecial::create([
            'cycle_id'   => $cycle->id,
            'client_nom' => $cycle->client_nom, // nom du client pour affichage
            'montant'    => $montant,
            'devise'     => $cycle->devise,
            // 'description'=> 'Ouverture du cycle',
        ]);
    }

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
