<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'created_at'], 'products_active_created_at_index');
            $table->index(['is_active', 'price'], 'products_active_price_index');
            $table->index(['is_active', 'review_count'], 'products_active_review_count_index');
            $table->index(['is_active', 'rating'], 'products_active_rating_index');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->index(['product_id', 'is_primary'], 'product_images_product_primary_index');
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex('product_images_product_primary_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_rating_index');
            $table->dropIndex('products_active_review_count_index');
            $table->dropIndex('products_active_price_index');
            $table->dropIndex('products_active_created_at_index');
        });
    }
};
