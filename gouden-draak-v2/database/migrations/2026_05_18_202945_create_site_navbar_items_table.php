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
        Schema::create('site_navbar_items', function (Blueprint $table) {
            $table->id();
            $table->json('header');
            $table->string('foreign_url')->nullable();

            $table->foreignId('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages');

            $table->integer('order', false, true);
            $table->unique('order');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_navbar_items');
    }
};
