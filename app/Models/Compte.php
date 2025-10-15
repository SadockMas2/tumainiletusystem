<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compte extends Model
{
    protected $fillable = [
        'numero_membre',
        'client_id',
        'numero_compte',
        'devise',
        'solde',
        'statut',

    ];

     public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }

    protected $casts = [
        'solde' => 'decimal:2'
    ];
    protected static function boot()
{
    parent::boot();

    static::creating(function ($compte) {
        if ($compte->client) {
            // Récupérer le numéro du membre depuis le client
            $compte->numero_membre = $compte->client->numero_membre;

            // Remplir nom/prénom/postnom
            $compte->nom = $compte->client->nom ?? '';
            $compte->postnom = $compte->client->postnom ?? '';
            $compte->prenom = $compte->client->prenom ?? '';

            // Générer le numéro de compte C00001, C00002...
            $lastCompte = self::latest('id')->first();
            $lastNumber = $lastCompte ? intval(substr($lastCompte->numero_compte, 1)) : 0;
            $newNumber = $lastNumber + 1;
            $compte->numero_compte = 'C' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        }
    });
}


  public function credits(): HasMany
    {
        return $this->hasMany(Credit::class, 'compte_id'); // ou le nom correct de la clé étrangère
    }

    // Relation avec le client
  
}
