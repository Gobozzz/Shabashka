<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->json('images')->nullable();
            $table->string('address');
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('payment_type'); // тип оплаты: договорная, фиксированная, ...
            $table->unsignedMediumInteger('price')->nullable();
            $table->string('payment_per')->nullable(); // за что оплата: за час, за день, ...
            $table->unsignedTinyInteger('need_count_workers')->nullable();
            $table->unsignedTinyInteger('status');
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
