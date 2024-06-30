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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();;
            $table->string('category');
            $table->text('description')->nullable();
            $table->integer('stocks')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); // User who last updated the record
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null'); // Ensuring foreign key constraint with users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
