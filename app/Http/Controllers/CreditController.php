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

            // Convertir les valeurs
            $montantPrincipal = (float) $request->montant;
            $duree = (int) $request->duree;
            $tauxInteret = (float) $request->taux_interet;
            
            // Calculer le montant total avec intérêts
            $montantTotal = $montantPrincipal * (1 + ($tauxInteret / 100));

            $credit = Credit::create([
                'compte_id' => $request->compte_id,
                'client_id' => $compte->client_id,
                'cycle_id' => 1,
                'montant_principal' => $montantPrincipal,
                'taux_interet' => $tauxInteret,
                'montant_total' => $montantTotal,
                'devise' => $compte->devise,
                'statut' => 'en_attente', // Statut initial
                'statut_demande' => 'en_attente', // Demande en attente
                'date_octroi' => null, // Sera défini lors de l'approbation
                'date_echeance' => null, // Sera défini lors de l'approbation
                'date_demande' => now()
            ]);

            DB::commit();

            return redirect('/admin/comptes')
                ->with('success', 'Demande de crédit soumise avec succès! Elle est maintenant en attente d\'approbation.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du crédit: ' . $e->getMessage())
                ->withInput();
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