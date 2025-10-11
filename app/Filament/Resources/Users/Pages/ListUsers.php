<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
{
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user?->hasRole('Admin')) {
        return [
            CreateAction::make(),
        ];
    }

    return []; // Aucun bouton pour les non-admins
}



    
}
