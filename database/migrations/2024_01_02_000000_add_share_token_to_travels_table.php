<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->string('share_token')->nullable()->unique()->after('itinerary_path');
            $table->timestamp('share_expires_at')->nullable()->after('share_token');
        });
    }

    public function down(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'share_expires_at']);
        });
    }
};
