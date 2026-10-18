<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->boolean('noindex')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'canonical_url',
                'noindex',
            ]);
        });
    }
};
