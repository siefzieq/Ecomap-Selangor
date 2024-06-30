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
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_type');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('category');
            $table->unsignedBigInteger('uploaded_by')->nullable(); // User who last updated the record
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null'); // Ensuring foreign key constraint with users table
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
