<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Address Label
            |--------------------------------------------------------------------------
            */

            $table->string('title')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Receiver
            |--------------------------------------------------------------------------
            */

            $table->string('full_name');

            $table->string('phone', 30);

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->string('country', 100)
                ->default('Iran');

            $table->string('province', 100);

            $table->string('city', 100);

            $table->string('postal_code', 20)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            $table->text('address');

            /*
            |--------------------------------------------------------------------------
            | Default Address
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_default')
                ->default(false);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'user_id',
                'is_default',
            ]);

            $table->index([
                'user_id',
                'city',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
