<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Compte;
use App\Models\CompteTransitoire;

class Epargne extends Model
{
    protected $fillable = [
        'client_id',
        'cycle_id',
        'user_id',
        'agent_nom',
        'montant',
        'date_apport',
        'premiere_mise',
        'statut',
        'devise',
        'numero_compte_membre',
        'solde_apres_membre',
        'client_nom',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function agent()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function cycle()
    {
        return $this->belongsTo(\App\Models\Cycle::class, 'cycle_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($epargne) {
            // Nom complet du client
            $client = \App\Models\Client::find($epargne->client_id);
            $agent = \App\Models\User::find($epargne->user_id);

            $epargne->client_nom = $client ? "{$client->nom} {$client->postnom} {$client->prenom}" : 'Inconnu';
            $epargne->agent_nom = $agent ? $agent->name : 'Inconnu';

            // Date et devise
            $epargne->date_apport = $epargne->date_apport ?: now();
            $epargne->devise = $epargne->devise ?: ($epargne->cycle->devise ?? 'CDF');

            // Statut initial
            $epargne->statut = 'en_attente_dispatch';

            // Compte transitoire de l’agent
            $ct = CompteTransitoire::firstOrCreate(
                ['user_id' => $epargne->user_id, 'devise' => $epargne->devise],
                ['solde' => 0, 'statut' => 'actif', 'agent_nom' => $epargne->agent_nom]
            );
            $ct->solde += $epargne->montant;
            $ct->save();

            // Mettre à jour le compte du client
            $compte = Compte::firstOrCreate(
                ['client_id' => $epargne->client_id, 'devise' => $epargne->devise],
                ['solde' => 0, 'numero_compte' => 'C'.str_pad($epargne->client_id, 6, '0', STR_PAD_LEFT)]
            );
            $compte->solde += $epargne->montant;
            $compte->save();

            $epargne->numero_compte_membre = $compte->numero_compte;
            $epargne->solde_apres_membre = $compte->solde;
        });
    }
}
