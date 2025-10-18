<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mouvement extends Model
{
    protected $fillable = [
        'compte_id',
        'numero_compte',
        'client_nom',
        'nom_deposant',
        'type',
        'montant',
        'solde_apres',
        'description',
        'operateur_id', // Nouveau champ pour l'utilisateur connecté
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function operateur()
    {
        return $this->belongsTo(User::class, 'operateur_id');
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

            // Adapter le nom_deposant selon le type de mouvement
            if ($mouvement->type === 'retrait') {
                $mouvement->nom_deposant = 'Retrait';
            }

            // Enregistrer l'utilisateur connecté
            $mouvement->operateur_id = Auth::id();

            // Mettre à jour le solde
            if ($mouvement->type === 'depot') {
                $compte->solde += $mouvement->montant;
            } elseif ($mouvement->type === 'retrait') {
                if ($compte->solde < $mouvement->montant) {
                    throw new \Exception('Solde insuffisant pour ce retrait');
                }
                $compte->solde -= $mouvement->montant;
            }

            $mouvement->solde_apres = $compte->solde;
            $compte->save();
        });
    }

    // Méthode pour générer le numéro de référence
    public function getNumeroReferenceAttribute()
    {
        return str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    // Méthode pour obtenir le nom abrégé de l'opérateur
    public function getOperateurAbregeAttribute()
    {
        if (!$this->operateur) return 'N/A';
        
        $nom = substr($this->operateur->name, 0, 1) ?? '';
        $postnom = substr($this->operateur->postnom ?? '', 0, 1) ?? '';
        
        return $nom . $postnom . '-' . $this->operateur_id;
    }
}