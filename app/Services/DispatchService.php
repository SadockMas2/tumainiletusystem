<?php

namespace App\Services;

use App\Models\Epargne;
use App\Models\CompteTransitoire;
use App\Models\Compte;
use App\Models\Mouvement;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DispatchService
{
    /**
     * Dispatcher l'épargne d'un groupe entre ses membres
     */
    public function dispatcherEpargneGroupe(int $epargneId, array $repartitions): bool
    {
        return DB::transaction(function () use ($epargneId, $repartitions) {
            $epargne = Epargne::findOrFail($epargneId);
            
            // Vérifications
            if ($epargne->type_epargne !== 'groupe_solidaire') {
                throw new \Exception('Seules les épargnes de groupe peuvent être dispatchées');
            }
            
            if ($epargne->statut !== 'en_attente_dispatch') {
                throw new \Exception('Cette épargne a déjà été dispatchée');
            }
            
            $montantTotal = $epargne->montant;
            $totalReparti = collect($repartitions)->sum('montant');
            
            // Vérifier l'équilibre
            if ($totalReparti != $montantTotal) {
                throw new \Exception("Le total réparti ({$totalReparti}) ne correspond pas au montant collecté ({$montantTotal})");
            }
            
            // Débiter le compte transitoire
            $compteTransitoire = CompteTransitoire::where('user_id', $epargne->user_id)
                ->where('devise', $epargne->devise)
                ->firstOrFail();
                
            if ($compteTransitoire->solde < $montantTotal) {
                throw new \Exception('Solde transitoire insuffisant');
            }
            
            $compteTransitoire->solde -= $montantTotal;
            $compteTransitoire->save();
            
            // Créditer les comptes des membres
            foreach ($repartitions as $repartition) {
                $this->crediterCompteMembre(
                    $repartition['membre_id'],
                    $repartition['montant'],
                    $epargne->devise,
                    $epargne->agent_nom,
                    $epargne->groupeSolidaire->nom_groupe
                );
            }
            
            // Marquer comme dispatché
            $epargne->statut = 'valide';
            $epargne->save();
            
            return true;
        });
    }
    
    /**
     * Créditer le compte d'un membre
     */
    private function crediterCompteMembre(int $membreId, float $montant, string $devise, string $agentNom, string $nomGroupe): void
    {
        $client = Client::findOrFail($membreId);
        
        $compte = Compte::firstOrCreate(
            [
                'client_id' => $membreId,
                'devise' => $devise,
                'type_compte' => 'individuel'
            ],
            [
                'solde' => 0,
                'numero_compte' => 'C' . str_pad($membreId, 6, '0', STR_PAD_LEFT),
                'nom' => $client->nom,
                'postnom' => $client->postnom,
                'prenom' => $client->prenom,
                'statut' => 'actif'
            ]
        );
        
        $compte->solde += $montant;
        $compte->save();
        
        // Enregistrer le mouvement
        Mouvement::create([
            'compte_id' => $compte->id,
            'numero_compte' => $compte->numero_compte,
            'client_nom' => $client->nom_complet,
            'nom_deposant' => $agentNom,
            'type' => 'depot',
            'montant' => $montant,
            'solde_apres' => $compte->solde,
            'description' => "Dispatch épargne groupe: {$nomGroupe}",
        ]);
    }
    
    /**
     * Récupérer les épargnes en attente de dispatch pour aujourd'hui
     */
    public function getEpargnesEnAttenteDispatch(): \Illuminate\Database\Eloquent\Collection
    {
        return Epargne::where('type_epargne', 'groupe_solidaire')
            ->where('statut', 'en_attente_dispatch')
            ->whereDate('created_at', today())
            ->with(['groupeSolidaire', 'groupeSolidaire.membres'])
            ->get();
    }
}