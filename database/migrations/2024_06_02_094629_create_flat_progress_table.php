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
        Schema::create('flat_progress', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('expected_date');
            $table->foreignId('flat_id')->constrained('flats');
            $table->string('planting_type');
            $table->foreignId('plantation_id')->constrained('plantations');
            $table->double('area_planted');
            $table->string('stage');
            $table->string('progress_status');
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
        Schema::dropIfExists('flat_progress');
    }
};
