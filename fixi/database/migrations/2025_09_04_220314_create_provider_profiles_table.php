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
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->text('bio')->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->string('status')->nullable(); //Pending, approved, rejected

            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('total_reviews')->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_orders_completed')->default(0);

            $table->boolean('serves_pf')->default(false);
            $table->boolean('serves_pj')->default(false);

            $table->foreignId('address_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};
