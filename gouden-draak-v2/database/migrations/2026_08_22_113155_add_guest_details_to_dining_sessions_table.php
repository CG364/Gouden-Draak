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
        Schema::table('dining_sessions', function (Blueprint $table) {
            $table->unsignedTinyInteger('guest_count')->after('table_id')->default(1);
            $table->json('guest_ages')->after('guest_count')->default('[]');
            $table->boolean('wants_extra_deluxe_menu')->after('guest_ages')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dining_sessions', function (Blueprint $table) {
            $table->dropColumn(['guest_count', 'guest_ages', 'wants_extra_deluxe_menu']);
        });
    }
};
