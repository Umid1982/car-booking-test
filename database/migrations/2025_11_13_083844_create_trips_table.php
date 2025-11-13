<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->restrictOnDelete()
                ->comment('Автомобиль, закреплённый за данной поездкой');

            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()
                ->comment('Пользователь, забронировавший автомобиль');

            $table->dateTime('start_at')
                ->comment('Дата и время начала поездки');

            $table->dateTime('end_at')
                ->comment('Дата и время окончания поездки');

            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])
                ->default('planned')
                ->comment('Статус поездки');

            $table->timestamps();

            // Индекс для оптимизации поиска пересекающихся интервалов
            $table->index(['start_at', 'end_at'], 'trips_start_end_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
