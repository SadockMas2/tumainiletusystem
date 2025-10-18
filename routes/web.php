<?php

use App\Http\Controllers\CompteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreditController;
use App\Models\Mouvement;

Route::get('/', function () {
    return view('welcome');
});

// Routes pour les crédits
Route::prefix('credits')->group(function () {
    Route::get('create/{compte_id}', [CreditController::class, 'create'])->name('credits.create');
    Route::post('/', [CreditController::class, 'store'])->name('credits.store');
    Route::get('payer/{compte_id}', [CreditController::class, 'payer'])->name('credits.payer');
    Route::put('{credit}', [CreditController::class, 'update'])->name('credits.update');
    
    // Nouvelles routes pour l'approbation
    Route::get('accorder/{credit_id}', [CompteController::class, 'accorderCredit'])->name('credits.accorder');
    Route::post('traiter-approbation/{credit_id}', [CompteController::class, 'traiterApprobation'])->name('credits.traiter-approbation');
    Route::post('annuler/{credit_id}', [CompteController::class, 'annulerDemande'])->name('credits.annuler');
});

// Routes pour les comptes
Route::get('comptes/{compte_id}/details', [CompteController::class, 'details'])->name('comptes.details');
Route::get('comptes', [CompteController::class, 'index'])->name('comptes.index');




Route::get('/mouvement/{mouvement}/bordereau', function (Mouvement $mouvement) {
    return view('bordereau-mouvement', compact('mouvement'));
})->name('mouvement.bordereau');