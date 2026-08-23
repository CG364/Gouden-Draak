<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreign('table_id')->references('id')->on('tables')->cascadeOnDelete();
            $table->index('handled');

            $table->foreignId('dining_session_id')
                ->nullable()
                ->after('table_id')
                ->constrained('dining_sessions')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dining_session_id');
            $table->dropIndex(['handled']);
            $table->dropForeign(['table_id']);
        });
    }
};
