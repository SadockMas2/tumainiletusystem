<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'compte_id',
        'numero,_compte',
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

            // Remplir le numéro du compte et le nom du client
            $mouvement->numero_compte = $compte->numero_compte;
            $mouvement->client_nom = $compte->nom . ' ' . $compte->postnom . ' ' . $compte->prenom;

            // Mettre à jour le solde
            if ($mouvement->type === 'depot') {
                $compte->solde += $mouvement->montant;
            } elseif ($mouvement->type === 'retrait') {
                $compte->solde -= $mouvement->montant;
            }

            $mouvement->solde_apres = $compte->solde;
            $compte->save();
        });
    }
}
