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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();

            $table->string('olx_id')->unique();
            $table->string('url');
            $table->string('title')->nullable();

            $table->unsignedBigInteger('last_price_value')->nullable();
            $table->string('last_currency', 3)->nullable(); // UAH, USD, EUR

            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('last_notified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
