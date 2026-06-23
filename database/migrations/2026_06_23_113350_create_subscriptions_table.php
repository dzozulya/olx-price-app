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

            $table->boolean('is_verified')->default(false);
            $table->string('verification_token')->nullable();

            $table->timestamps();

            $table->unique(['advertisement_id', 'email']);
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
