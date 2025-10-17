<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Services\TransactionService;
use App\Models\Client;
use App\Models\Coffre;
use App\Models\CompteBancaire;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // ✅ C'est cette méthode qu'on utilise maintenant :
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // On peut modifier ou enrichir $data avant création
        return $data;
    }

    // ✅ C’est ici qu’on effectue la logique métier réelle
    protected function handleRecordCreation(array $data): Model
    {
        $service = new TransactionService();

        $type = $data['type'];
        $client = isset($data['client_id']) ? Client::find($data['client_id']) : null;

        // Logique selon le type de transaction
        switch ($type) {
            case 'approvisionnement':
                $source = CompteBancaire::first(); // Banque
                $destination = Coffre::first(); // Coffre
                break;

            case 'decaissement':
                $source = Coffre::first();
                $destination = CompteBancaire::first();
                break;

            case 'credit':
                $source = Coffre::first();
                $destination = $client;
                break;

            case 'remboursement':
                $source = $client;
                $destination = Coffre::first();
                break;

            default:
                $source = null;
                $destination = null;
                break;
        }

        // Exécuter la logique métier
        return $service->effectuer(
            $type,
            $source,
            $destination,
            $data['montant'],
            $data['devise'],
            $client,
            $data['description'] ?? null
        );
    }
}
