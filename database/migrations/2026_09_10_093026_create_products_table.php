<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();

            $table->string('brand')->nullable();

            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            |
            | Prices are stored as integer values.
            | Example:
            | 12500000 = 12,500,000
            |
            */
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('old_price')->nullable();

            $table->unsignedTinyInteger('discount')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('stock')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Reviews
            |--------------------------------------------------------------------------
            */
            $table->decimal('rating', 2, 1)
                ->default(0);

            $table->unsignedInteger('review_count')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')
                ->default(true);

            $table->boolean('is_featured')
                ->default(false);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index([
                'category_id',
                'is_active',
            ]);

            $table->index([
                'is_active',
                'is_featured',
            ]);

            $table->index('brand');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
