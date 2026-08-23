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
        Schema::create('table_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreignId('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_plannings');
    }
};
