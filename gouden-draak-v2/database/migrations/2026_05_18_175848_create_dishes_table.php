<?php

use App\Models\DishKind;
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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('menu_number');
            $table->json('name');
            $table->json('description');

            $table->foreignId('dish_kind');
            $table->foreign('dish_kind')
                ->references('id')
                ->on('dish_kinds')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
