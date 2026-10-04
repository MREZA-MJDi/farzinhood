<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type');

            $table->integer('quantity');

            $table->unsignedInteger('stock_before');

            $table->unsignedInteger('stock_after');

            /*
            |--------------------------------------------------------------------------
            | Polymorphic Reference
            |--------------------------------------------------------------------------
            |
            | Example:
            | order / 1001
            | manual / 25
            |
            */

            $table->nullableMorphs('reference');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index([
                'product_id',
                'type',
            ]);

            $table->index([
                'product_id',
                'created_at',
            ]);

            $table->index([
                'user_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
