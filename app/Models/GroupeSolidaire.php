<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeSolidaire extends Model
{
    use HasFactory;

    protected $table = 'groupes_solidaires';

    protected $fillable = [
        'numero_groupe',
        'nom_groupe',
        'numero_cycle',
        'adresse',
        'date_debut_cycle',
        'date_fin_cycle',
    ];

    public function membres()
    {
        return $this->belongsToMany(Client::class, 'groupes_membres', 'groupe_solidaire_id', 'client_id');
    }

    // Nouvelle relation avec les comptes
    public function comptes()
    {
        return $this->hasMany(Compte::class, 'groupe_solidaire_id');
    }

    // Compte USD du groupe
    public function compteUSD()
    {
        return $this->hasOne(Compte::class, 'groupe_solidaire_id')->where('devise', 'USD');
    }

    // Compte CDF du groupe
    public function compteCDF()
    {
        return $this->hasOne(Compte::class, 'groupe_solidaire_id')->where('devise', 'CDF');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($groupe) {
            if (!$groupe->numero_groupe) {
                $last = self::max('numero_groupe') ?? 300000;
                $groupe->numero_groupe = $last + 1;
            }
        });

        // Créer automatiquement les comptes USD et CDF lors de la création du groupe
        static::created(function ($groupe) {
            $groupe->creerComptesGroupes();
        });
    }

    public function creerComptesGroupes()
    {
        // Créer le compte USD
        Compte::create([
            'groupe_solidaire_id' => $this->id,
            'numero_compte' => $this->genererNumeroCompteGroupe(),
            'nom' => $this->nom_groupe,
            'devise' => 'USD',
            'solde' => 0,
            'statut' => 'actif',
            'type_compte' => 'groupe_solidaire'
        ]);

        // Créer le compte CDF
        Compte::create([
            'groupe_solidaire_id' => $this->id,
            'numero_compte' => $this->genererNumeroCompteGroupe(),
            'nom' => $this->nom_groupe,
            'devise' => 'CDF',
            'solde' => 0,
            'statut' => 'actif',
            'type_compte' => 'groupe_solidaire'
        ]);
    }

    private function genererNumeroCompteGroupe()
    {
        $lastCompte = Compte::where('type_compte', 'groupe_solidaire')
            ->latest('id')
            ->first();
        
        $lastNumber = $lastCompte ? intval(substr($lastCompte->numero_compte, 2)) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'GS' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}