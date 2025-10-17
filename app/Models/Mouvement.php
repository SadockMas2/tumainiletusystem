<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'compte_id',
        'numero_compte', // Correction: enlevé la virule mal placée
        'client_nom',
        'nom_deposant',
        'type',
        'montant',
        'solde_apres',
        'description',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mouvement) {
            $compte = $mouvement->compte;

            if (!$compte) {
                throw new \Exception('Compte introuvable pour ce mouvement');
            }

            // Remplir le numéro du compte
            $mouvement->numero_compte = $compte->numero_compte;

            // Remplir le nom selon le type de compte
            if ($compte->type_compte === 'groupe_solidaire') {
                $mouvement->client_nom = $compte->nom . ' (Groupe)';
            } else {
                $mouvement->client_nom = $compte->nom . ' ' . $compte->postnom . ' ' . $compte->prenom;
            }

            // Mettre à jour le solde
            if ($mouvement->type === 'depot') {
                $compte->solde += $mouvement->montant;
            } elseif ($mouvement->type === 'retrait') {
                // Vérifier que le solde est suffisant
                if ($compte->solde < $mouvement->montant) {
                    throw new \Exception('Solde insuffisant pour ce retrait');
                }
                $compte->solde -= $mouvement->montant;
            }

            $mouvement->solde_apres = $compte->solde;
            $compte->save();
        });
    }
}