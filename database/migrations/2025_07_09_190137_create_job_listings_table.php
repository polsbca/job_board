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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('company');
            $table->string('location');
            $table->string('category');
            $table->decimal('salary', 10, 2);
            $table->enum('type', ['full-time', 'part-time', 'contract', 'freelance', 'internship']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('closing_date')->nullable();
            $table->timestamps();
            
            // Add index for better performance on search queries
            $table->index(['status', 'closing_date']);
            $table->index(['location', 'category', 'type']);
            $table->index('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
