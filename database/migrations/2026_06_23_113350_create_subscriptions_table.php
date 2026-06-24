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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advertisement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('email');

            $table->timestamp('verified_at')->nullable();
            $table->string('verification_token')->nullable()->unique();
            $table->timestamp('verification_token_expires_at')->nullable();

            $table->timestamps();

            $table->unique(['advertisement_id', 'email']);
            $table->index(['advertisement_id', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
