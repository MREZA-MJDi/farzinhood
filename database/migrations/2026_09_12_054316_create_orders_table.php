<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Order Identity
            |--------------------------------------------------------------------------
            */

            $table->string('order_number')->unique();

            /*
            |--------------------------------------------------------------------------
            | Order Status
            |--------------------------------------------------------------------------
            |
            | pending
            | processing
            | shipped
            | delivered
            | cancelled
            |
            */

            $table->string('status')->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            |
            | pending
            | paid
            | failed
            | refunded
            |
            */

            $table->string('payment_status')->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('subtotal')->default(0);

            $table->unsignedBigInteger('discount')->default(0);

            $table->unsignedBigInteger('shipping_cost')->default(0);

            $table->unsignedBigInteger('total')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Shipping Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('shipping_full_name');

            $table->string('shipping_phone');

            $table->string('shipping_country')->nullable();

            $table->string('shipping_province')->nullable();

            $table->string('shipping_city');

            $table->string('shipping_postal_code')->nullable();

            $table->text('shipping_address');

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'status',
            ]);

            $table->index('payment_status');

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
