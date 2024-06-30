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
        Schema::create('seedings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('plant_id')->nullable()->constrained('plants');
            $table->foreignId('flat_id')->nullable()->constrained('flats');
            $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('seedings');
    }
};
