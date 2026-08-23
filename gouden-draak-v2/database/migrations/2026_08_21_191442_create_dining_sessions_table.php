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
        Schema::create('dining_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('table_id')
                ->constrained('tables')
                ->cascadeOnDelete();

            $table->foreignId('opened_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('token')->unique();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dining_sessions');
    }
};
