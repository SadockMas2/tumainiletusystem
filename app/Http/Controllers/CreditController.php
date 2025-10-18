<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Models\Credit;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    // Formulaire pour demander un crédit
    public function create($compte_id)
    {
        $compte = Compte::findOrFail($compte_id);
        
        return view('credits.create', [
            'compte' => $compte
        ]);
    }

    // Stocker un nouveau crédit
   public function store(Request $request)
{
    $request->validate([
        'compte_id' => 'required|exists:comptes,id',
        'montant' => 'required|numeric|min:1',
        'duree' => 'required|integer|min:1|max:120',
        'taux_interet' => 'required|numeric|min:0.1|max:50'
    ]);

    try {
        DB::beginTransaction();

        $compte = Compte::findOrFail($request->compte_id);

        $montant = (float) $request->montant;
        $dureeMois = (int) $request->duree;
        $taux = (float) $request->taux_interet;

        // 🧮 Nombre total de semaines
        $nbSemaines = $dureeMois * 4;

        // 🧮 Annuité du capital par semaine
        $annuite = $montant / $nbSemaines;

        // Variables de calcul
        $capitalRestant = $montant;
        $totalInteret = 0;
        $tableauRemboursements = [];

        // 🧾 Boucle de calcul hebdomadaire
        for ($i = 1; $i <= $nbSemaines; $i++) {
            $interet = $capitalRestant * ($taux / 100);
            $remboursement = $annuite + $interet;

            $tableauRemboursements[] = [
                'semaine' => $i,
                'capital_restant' => round($capitalRestant, 2),
                'interet' => round($interet, 2),
                'remboursement_total' => round($remboursement, 2)
            ];

            $totalInteret += $interet;
            $capitalRestant -= $annuite;
        }

        $montantTotal = $montant + $totalInteret;

        // 💾 Enregistrement du crédit
        $credit = Credit::create([
            'compte_id' => $request->compte_id,
            'client_id' => $compte->client_id,
            'cycle_id' => 1,
            'montant_principal' => $montant,
            'taux_interet' => $taux,
            'montant_total' => round($montantTotal, 2),
            'devise' => $compte->devise,
            'statut' => 'en_attente',
            'statut_demande' => 'en_attente',
            'date_octroi' => null,
            'date_echeance' => null,
            'date_demande' => now()
        ]);

        DB::commit();

        // 🔢 Sauvegarde possible du tableau (si tu veux créer un modèle `EcheanceCredit`)
        // foreach ($tableauRemboursements as $ligne) {
        //     EcheanceCredit::create([
        //         'credit_id' => $credit->id,
        //         'semaine' => $ligne['semaine'],
        //         'montant_interet' => $ligne['interet'],
        //         'montant_total' => $ligne['remboursement_total'],
        //         'capital_restant' => $ligne['capital_restant']
        //     ]);
        // }

        return redirect('/admin/comptes')
            ->with('success', "Demande de crédit soumise avec succès ! Montant total à rembourser : " . round($montantTotal, 2) . " " . $compte->devise);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Erreur lors de la création du crédit : ' . $e->getMessage());
    }
}

    // Formulaire pour payer un crédit
    public function payer($compte_id)
    {
        $compte = Compte::findOrFail($compte_id);
        
        // Récupérer les crédits avec le bon statut
        $credits = Credit::where('compte_id', $compte_id)
                        ->where('statut', 'en_cours')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        $credit = $credits->first();

        if (!$credit) {
            return redirect('/admin/comptes')
                ->with('error', 'Aucun crédit en cours trouvé pour ce compte.');
        }

        return view('credits.payer', compact('compte', 'credit'));
    }

    // Mettre à jour le paiement
    public function update(Request $request, $id)
    {
        $request->validate([
            'montant_paye' => 'required|numeric|min:0.01'
        ]);

        $credit = Credit::findOrFail($id);
        
        // Convertir le montant payé en float
        $montantPaye = (float) $request->montant_paye;
        
        // Logique de paiement
        $nouveauMontantTotal = $credit->montant_total - $montantPaye;
        
        if ($nouveauMontantTotal <= 0) {
            $credit->update([
                'montant_total' => 0,
                'statut' => 'remboursé'
            ]);
        } else {
            $credit->update([
                'montant_total' => $nouveauMontantTotal
            ]);
        }

        return redirect('/admin/comptes')
            ->with('success', 'Paiement effectué avec succès');
    }
}