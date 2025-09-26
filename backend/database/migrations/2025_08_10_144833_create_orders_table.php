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
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('pending');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_postal_code')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
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
