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
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            
            // Use unsignedBigInteger instead of foreignId for more control
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints separately after table creation
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('job_id')
                  ->references('id')
                  ->on('job_listings')
                  ->onDelete('cascade');
            
            // Ensure a user can't save the same job multiple times
            $table->unique(['user_id', 'job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};
