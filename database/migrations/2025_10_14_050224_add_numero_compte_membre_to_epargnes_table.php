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
            Schema::table('epargnes', function (Blueprint $table) {
                $table->timestamp('numero_compte_membre')->nullable()->after('client_nom');
            });
        }

        public function down(): void
        {
            Schema::table('epargnes', function (Blueprint $table) {
                $table->dropColumn('numero_compte_membre');
            });
        }

};
