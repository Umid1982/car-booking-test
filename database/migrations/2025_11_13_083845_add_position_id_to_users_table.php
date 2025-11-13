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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('position_id')
                ->nullable()
                ->after('id') // ставим ближе к началу для читабельности структуры
                ->constrained('positions')
                ->nullOnDelete()
                ->comment('Должность пользователя (nullable, так как некоторые пользователи могут быть без привязки)');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем внешний ключ + само поле
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};
