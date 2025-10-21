<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compte extends Model
{
    protected $fillable = [
        'numero_membre',
        'client_id',
        'groupe_solidaire_id',
        'numero_compte',
        'devise',
        'solde',
        'statut',
        'type_compte',
        'nom',
        'postnom',
        'prenom'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function groupeSolidaire()
    {
        return $this->belongsTo(GroupeSolidaire::class, 'groupe_solidaire_id');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class, 'compte_id');
    }

    protected $casts = [
        'solde' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compte) {
            // Si c'est un compte individuel
            if ($compte->client_id && $compte->client) {
                $compte->numero_membre = $compte->client->numero_membre;
                $compte->nom = $compte->client->nom ?? '';
                $compte->postnom = $compte->client->postnom ?? '';
                $compte->prenom = $compte->client->prenom ?? '';
                $compte->type_compte = 'individuel';

                if (empty($compte->numero_compte)) {
                    $lastCompte = self::where('type_compte', 'individuel')->latest('id')->first();
                    $lastNumber = $lastCompte ? intval(substr($lastCompte->numero_compte, 1)) : 0;
                    $newNumber = $lastNumber + 1;
                    $compte->numero_compte = 'C' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                }
            }
            // Si c'est un compte de groupe
            elseif ($compte->groupe_solidaire_id && $compte->groupeSolidaire) {
                $compte->nom = $compte->groupeSolidaire->nom_groupe;
                $compte->type_compte = 'groupe_solidaire';

                // Générer automatiquement le numéro de compte pour les groupes avec format GS
                if (empty($compte->numero_compte)) {
                    $lastCompte = self::where('type_compte', 'groupe_solidaire')
                        ->where('numero_compte', 'LIKE', 'GS%')
                        ->latest('id')
                        ->first();
                    
                    $lastNumber = $lastCompte ? intval(substr($lastCompte->numero_compte, 2)) : 0;
                    $newNumber = $lastNumber + 1;
                    $compte->numero_compte = 'GS' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    // Scope pour filtrer les comptes par type
    public function scopeIndividuels($query)
    {
        return $query->where('type_compte', 'individuel');
    }

    public function scopeGroupesSolidaires($query)
    {
        return $query->where('type_compte', 'groupe_solidaire');
    }
}