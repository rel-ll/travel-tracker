<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->string('transport_provider')->nullable()->after('travel_mode');
        });
    }

    public function down(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->dropColumn('transport_provider');
        });
    }
};
