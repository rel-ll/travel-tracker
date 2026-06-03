<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->id();
            $table->enum('travel_mode', ['air', 'sea', 'land']);
            $table->string('origin');               // From where
            $table->string('destination');          // To where
            $table->string('purpose');              // Purpose / Project name
            $table->decimal('amount', 10, 2);       // Cost/Budget
            $table->unsignedInteger('passengers');  // Number of passengers
            $table->string('itinerary_path')->nullable(); // Uploaded file
            $table->date('travel_date');            // Date of travel
            $table->date('return_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
