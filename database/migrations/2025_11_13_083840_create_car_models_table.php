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
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->string('brand')
                ->comment('Марка автомобиля (например: Toyota, BMW)');

            $table->string('name')
                ->comment('Название модели автомобиля (например: Camry)');

            $table->foreignId('comfort_category_id')
                ->constrained('comfort_categories')
                ->comment('Категория комфорта, к которой относится эта модель');

            // Правильное ограничение — уникальная пара (brand + name)
            $table->unique(['brand', 'name'], 'car_models_brand_name_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
