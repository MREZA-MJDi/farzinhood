<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $addIndexIfMissing = function (string $tableName, array $columns, string $indexName): void {
            if (! Schema::hasTable($tableName)) {
                return;
            }

            $exists = collect(Schema::getIndexes($tableName))
                ->contains(fn (array $index) => ($index['name'] ?? null) === $indexName);

            if (! $exists) {
                Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName) {
                    $table->index($columns, $indexName);
                });
            }
        };

        $addIndexIfMissing('products', ['is_active', 'created_at'], 'products_active_created_at_index');
        $addIndexIfMissing('products', ['is_active', 'price'], 'products_active_price_index');
        $addIndexIfMissing('products', ['is_active', 'review_count'], 'products_active_review_count_index');
        $addIndexIfMissing('products', ['is_active', 'rating'], 'products_active_rating_index');
        $addIndexIfMissing('product_images', ['product_id', 'is_primary'], 'product_images_product_primary_index');
    }

    public function down(): void
    {
        $dropIndexIfExists = function (string $tableName, string $indexName): void {
            if (! Schema::hasTable($tableName)) {
                return;
            }

            $exists = collect(Schema::getIndexes($tableName))
                ->contains(fn (array $index) => ($index['name'] ?? null) === $indexName);

            if ($exists) {
                Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            }
        };

        $dropIndexIfExists('product_images', 'product_images_product_primary_index');
        $dropIndexIfExists('products', 'products_active_rating_index');
        $dropIndexIfExists('products', 'products_active_review_count_index');
        $dropIndexIfExists('products', 'products_active_price_index');
        $dropIndexIfExists('products', 'products_active_created_at_index');
    }
};
