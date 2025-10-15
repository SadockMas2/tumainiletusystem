<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    protected $fillable = [
        'compte_id',
        'client_id',
        'cycle_id',
        'montant_principal',
        'taux_interet',
        'montant_total',
        'devise',
        'statut',
        'statut_demande',
        'date_octroi',
        'date_echeance',
        'date_demande',
        'date_approbation',
        'motif_rejet'
    ];

    protected $casts = [
        'montant_principal' => 'decimal:2',
        'taux_interet' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_octroi' => 'date',
        'date_echeance' => 'date',
        'date_demande' => 'datetime',
        'date_approbation' => 'datetime'
    ];

    // Scope pour les demandes en attente
    public function scopeEnAttente($query)
    {
        return $query->where('statut_demande', 'en_attente');
    }

    // Scope pour les crédits approuvés
    public function scopeApprouves($query)
    {
        return $query->where('statut_demande', 'approuve');
    }

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class, 'compte_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Accesseurs
    public function getMontantAttribute()
    {
        return $this->montant_principal;
    }

    public function getMontantRestAttribute()
    {
        return $this->montant_total;
    }

    public function getDureeAttribute()
    {
        if ($this->date_octroi && $this->date_echeance) {
            $start = \Carbon\Carbon::parse($this->date_octroi);
            $end = \Carbon\Carbon::parse($this->date_echeance);
            return $start->diffInMonths($end);
        }
        return 0;
    }

    // Méthodes pour gérer les statuts
    public function estEnAttente()
    {
        return $this->statut_demande === 'en_attente';
    }

    public function estApprouve()
    {
        return $this->statut_demande === 'approuve';
    }

    public function estRejete()
    {
        return $this->statut_demande === 'rejete';
    }

    public function estAnnule()
    {
        return $this->statut_demande === 'annule';
    }
}