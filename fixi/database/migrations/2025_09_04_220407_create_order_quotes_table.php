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
        Schema::create('order_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('provider_id')->constrained('users');
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('accepted')->default(false);
            $table->integer('estimated_time')->nullable();
            $table->string('estimated_time_unit')->nullable();
            $table->string('status')->default('sent'); //Sent,viewed,accepted,rejected
            $table->datetime('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_quotes');
    }
};
