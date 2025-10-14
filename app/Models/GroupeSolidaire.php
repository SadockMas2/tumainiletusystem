<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeSolidaire extends Model
{
    use HasFactory;

    protected $table = 'groupes_solidaires'; // <-- Nom exact de la table

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

    protected static function boot()
{
    parent::boot();

    static::creating(function ($groupe) {
        if (!$groupe->numero_groupe) {
            $last = self::max('numero_groupe') ?? 300000;
            $groupe->numero_groupe = $last + 1;
        }
    });
}

}
