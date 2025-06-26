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
        Schema::table('users', function (Blueprint $table) {
            // Add columns if not already in the users table
            $table->unsignedBigInteger('profile_photo_id')->nullable();
            $table->unsignedBigInteger('banner_photo_id')->nullable();
    
            // Now add foreign key constraints
            $table->foreign('profile_photo_id')
                ->references('id')
                ->on('post_media')
                ->nullOnDelete();
    
            $table->foreign('banner_photo_id')
                ->references('id')
                ->on('post_media')
                ->nullOnDelete();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_photo_id']);
            $table->dropForeign(['banner_photo_id']);
        });
    }
};
