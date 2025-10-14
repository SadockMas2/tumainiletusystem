<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    protected $fillable = [
        'devise',
        'solde',
        'nom',
        'statut'];

    public static function getByDevise(string $devise): ?self
    {
        return self::where('devise', $devise)->first();
    }

    public function credit(float $amount)
    {
        $this->solde += $amount;
        $this->save();
    }

    public function debit(float $amount): bool
    {
        if ($this->solde < $amount) return false;
        $this->solde -= $amount;
        $this->save();
        return true;
    }
}
