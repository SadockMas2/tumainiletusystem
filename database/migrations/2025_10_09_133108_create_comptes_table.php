<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('comptes', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('type_compte_id');
    $table->foreignId('produit_epargne_id')->constrained('produit_epargnes');   
    $table->string('numero_compte')->unique();
    $table->enum('type_compte', ['courant', 'épargne', 'autre'])->default('courant');
    $table->decimal('solde', 15, 2)->default(0.00);
    $table->date('date_ouverture')->useCurrent();
    $table->enum('statut', ['actif', 'inactif', 'ferme'])->default('actif');
    $table->string('devise', 3)->default('USD');
    $table->timestamps();

    $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
    $table->foreign('type_compte_id')->references('id')->on('type_comptes')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
