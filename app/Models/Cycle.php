<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_nom',
        'numero_cycle',   // numéro auto-incrémenté pour chaque nouveau cycle du client
        'date_debut',
        'date_fin',
        'devise',
        'solde_initial',
        'statut',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function epargnes()
    {
        return $this->hasMany(Epargne::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($cycle) {
            if ($cycle->client_id) {
                $client = \App\Models\Client::find($cycle->client_id);

                // Nom complet du client
                $cycle->client_nom = $client ? "{$client->nom} {$client->postnom} {$client->prenom}" : 'Inconnu';

                // Déterminer le numéro du cycle automatiquement
                $dernierCycle = self::where('client_id', $cycle->client_id)
                    ->orderBy('numero_cycle', 'desc')
                    ->first();
                $cycle->numero_cycle = $dernierCycle ? $dernierCycle->numero_cycle + 1 : 1;

                // Définir le statut initial
                $cycle->statut = 'ouvert';

                // Si devise ou solde initial non fournis, prendre des valeurs par défaut
                $cycle->devise = $cycle->devise ?: 'CDF';
                $cycle->solde_initial = $cycle->solde_initial ?: 0;
            }
        });
    }

    // Fermer le cycle
    public function fermer()
    {
        $this->statut = 'cloture';
        $this->date_fin = now();
        $this->save();
    }
}
