<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('epargnes', 'date_dispatch')) {
            Schema::table('epargnes', function (Blueprint $table) {
                $table->timestamp('date_dispatch')->nullable()->after('date_apport');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('epargnes', 'date_dispatch')) {
            Schema::table('epargnes', function (Blueprint $table) {
                $table->dropColumn('date_dispatch');
            });
        }
    }
};