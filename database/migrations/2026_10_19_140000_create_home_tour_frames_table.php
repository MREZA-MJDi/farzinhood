<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_tour_frames', function (Blueprint $table) {
            $table->id();
            $table->string('scene', 20);
            $table->string('image');
            $table->string('title', 160)->nullable();
            $table->text('description')->nullable();
            $table->string('alt', 180)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedTinyInteger('object_position_x')->default(50);
            $table->unsignedTinyInteger('object_position_y')->default(50);
            $table->decimal('scale', 4, 2)->default(1.03);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['scene', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_tour_frames');
    }
};
