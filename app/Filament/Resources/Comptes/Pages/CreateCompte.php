<?php

namespace App\Filament\Resources\Comptes\Pages;

use App\Filament\Resources\Comptes\CompteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth; // ✅ Import nécessaire

class CreateCompte extends CreateRecord
{
    protected static string $resource = CompteResource::class;

    /**
     * Détermine si l'utilisateur peut voir cette page
     */
    protected function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('admin');
    }
}
