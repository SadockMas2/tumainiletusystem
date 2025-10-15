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
        Schema::create('credits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('compte_id')->constrained()->onDelete('cascade');
        $table->foreignId('client_id')->constrained()->cascadeOnDelete();
        $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
        $table->string('client_nom');
        $table->decimal('montant_principal', 15, 2);
        $table->decimal('taux_interet', 5, 2)->default(0);
        $table->decimal('montant_total', 15, 2); // principal + intérêts
        $table->enum('statut', ['en_cours', 'remboursé', 'retard'])->default('en_cours');
        $table->date('date_octroi');
        $table->date('date_echeance');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
