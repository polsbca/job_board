<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'employer', 'applicant'])->default('applicant')->after('password');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('bio')->nullable()->after('phone');
            $table->string('profile_photo_path', 2048)->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'bio', 'profile_photo_path']);
        });
    }
};
