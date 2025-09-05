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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //Relations
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('provider_id')->nullable()->constrained('users');
            $table->foreignId('address_id')->constrained('addresses');

            //Infos
            $table->string('title');
            $table->text('description')->nullable();

            //Status
            $table->string('status')->default('pending'); //pending_quiotes, pending_payment, scheduled, in_progress, completed, cancelled, disputed
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();

            //Values
            $table->decimal('provider_fee', 10, 2)->nullable();
            $table->decimal('tax_fee', 10, 2)->nullable();
            $table->decimal('platform_fee', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();

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
