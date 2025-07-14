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
        Schema::create('user_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'single',
                'in_a_relationship',
                'engaged',
                'married',
                'complicated',
                'separated',
                'divorced',
                'widowed',
            ]);
            $table->foreignId('partner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('confirmed')->default(false);
            $table->date('since')->nullable();
            $table->enum('visibility', ['public', 'friends', 'only_me'])->default('public');
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relationships');
    }
};
