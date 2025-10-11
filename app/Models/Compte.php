<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    protected $fillable = [
        "client_id",
        "numero_compte",
        "type_compte",
        "solde",
        "date_ouverture",
        "satut",
        "devise",
        

    ];
}
