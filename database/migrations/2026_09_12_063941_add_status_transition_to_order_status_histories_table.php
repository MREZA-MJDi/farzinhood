<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->string('from_status')
                ->nullable()
                ->after('changed_by');

            $table->renameColumn(
                'status',
                'to_status'
            );
        });
    }

    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->renameColumn(
                'to_status',
                'status'
            );

            $table->dropColumn('from_status');
        });
    }
};
