<?php

namespace App\Services;

use App\Models\CompteTransitoire;
use App\Models\Epargne;
use App\Models\Caisse;
use App\Models\Dispatch;
use Illuminate\Support\Facades\DB;
use Exception;

class DispatchService
{
    /**
     * Exécute le dispatch pour un agent donné (tous les epargnes 'en_attente_dispatch' pour la date fournie).
     *
     * @param int $agentId
     * @param string|null $date Y-m-d or null = today
     * @param int $caissierId user_id qui fait l'opération
     * @return Dispatch
     * @throws Exception
     */
    public function performDispatch(int $agentId, ?string $date, int $caissierId): Dispatch
    {
        $date = $date ?: date('Y-m-d');
        // récupération de toutes les épargnes en attente pour cet agent et cette date
        $epargnes = Epargne::where('user_id', $agentId)
                    ->whereDate('created_at', $date)
                    ->where('statut', 'en_attente_dispatch')
                    ->get();

        if ($epargnes->isEmpty()) {
            throw new Exception("Aucune épargne à dispatcher pour l'agent {$agentId} le {$date}");
        }

        // grouper par devise (on peut faire par devise)
        $grouped = $epargnes->groupBy('devise');

        DB::beginTransaction();
        try {
            $dispatchTotal = 0;
            $dispatch = Dispatch::create([
                'user_id' => $caissierId,
                'agent_id' => $agentId,
                'devise' => $epargnes->first()->devise,
                'montant_total' => 0,
                'notes' => "Dispatch pour agent {$agentId} date {$date}",
                'statut' => 'effectue',
            ]);

            foreach ($grouped as $devise => $items) {
                // compte transitoire de l'agent pour la devise
                $ct = CompteTransitoire::where('user_id', $agentId)->where('devise', $devise)->first();
                if (!$ct) {
                    throw new Exception("Compte transitoire introuvable pour agent {$agentId} devise {$devise}");
                }

                $totalDevise = $items->sum('montant');

                // pour chaque épargne : appliquer règles
                foreach ($items as $e) {
                    // si première mise : tout va à la caisse (microfinance)
                    $caisse = Caisse::getByDevise($devise);
                    if (!$caisse) {
                        throw new Exception("Caisse introuvable pour la devise {$devise}");
                    }

                    if ($e->premiere_mise) {
                        // débiter le compte transitoire
                        if (!$ct->debit($e->montant)) {
                            throw new Exception("Solde transitoire insuffisant pour agent {$agentId}");
                        }
                        // créditer la caisse
                        $caisse->credit($e->montant);

                        // marquer epargne comme validée
                        $e->statut = 'valide';
                        $e->save();
                    } else {
                        // une mise normale : 
                        // 1) débiter le compte transitoire
                        if (!$ct->debit($e->montant)) {
                            throw new Exception("Solde transitoire insuffisant pour agent {$agentId}");
                        }

                        // 2) créditer le compte du membre (Compte model)
                        $compteMembre = \App\Models\Compte::where('client_id', $e->client_id)
                            ->where('devise', $devise)
                            ->first();

                        if (!$compteMembre) {
                            // si le membre n'a pas de compte pour cette devise, tu peux décider de le créer ou d'échouer.
                            // ici on échoue pour éviter perte.
                            throw new Exception("Compte membre introuvable pour client {$e->client_id} devise {$devise}");
                        }

                        $compteMembre->solde += $e->montant;
                        $compteMembre->save();

                        // 3) créditer la caisse interne associée au produit d'épargne (si besoin)
                        $caisse->credit($e->montant);

                        // 4) marquer epargne comme validée
                        $e->statut = 'valide';
                        $e->solde_apres_membre = $compteMembre->solde;
                        $e->save();
                    }
                }

                $dispatchTotal += $totalDevise;
            }

            // mettre à jour le dispatch
            $dispatch->montant_total = $dispatchTotal;
            $dispatch->save();

            DB::commit();
            return $dispatch;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
