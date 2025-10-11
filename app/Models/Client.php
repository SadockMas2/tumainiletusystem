<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'numero_membre',
        'nom',
        'postnom',
        'prenom',
        'date_naissance',
        'email',
        'telephone',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'id_createur',
        'status',
        'identifiant_national',
        'type_client',
        
        
    ];
}
