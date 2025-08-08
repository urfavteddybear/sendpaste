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
        Schema::create('pastes', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 6)->unique();
            $table->text('content'); // Encrypted content
            $table->string('title')->nullable();
            $table->string('language')->default('text');
            $table->timestamp('expires_at')->nullable();
            $table->string('password_hash')->nullable();
            $table->integer('views')->default(0);
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastes');
    }
};
