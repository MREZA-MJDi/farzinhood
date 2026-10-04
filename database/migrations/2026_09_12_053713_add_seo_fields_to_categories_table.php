<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('meta_title')
                ->nullable()
                ->after('is_active');

            $table->text('meta_description')
                ->nullable()
                ->after('meta_title');

            $table->string('canonical_url')
                ->nullable()
                ->after('meta_description');

            $table->boolean('noindex')
                ->default(false)
                ->after('canonical_url');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'canonical_url',
                'noindex',
            ]);
        });
    }
};
