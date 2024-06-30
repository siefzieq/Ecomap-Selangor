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
        Schema::create('plantations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants');
            $table->string('planting_type');
            $table->integer('seeding_duration'); //Number of days for seeding
            $table->integer('harvesting_duration'); //Number of days for harvesting
            $table->integer('completion_duration'); //Number of days for completion
            $table->unsignedBigInteger('updated_by')->nullable(); // User who last updated the record
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null'); // Ensuring foreign key constraint with users table
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantations');
    }
};
