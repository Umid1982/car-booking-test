<?php

namespace Database\Seeders;

use App\Models\ComfortCategory;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем должности
        $director = Position::create(['name' => 'Директор']);
        $manager = Position::create(['name' => 'Менеджер']);
        $employee = Position::create(['name' => 'Сотрудник']);

        // Получаем категории комфорта
        $firstCategory = ComfortCategory::where('name', 'Первая')->first();
        $secondCategory = ComfortCategory::where('name', 'Вторая')->first();
        $thirdCategory = ComfortCategory::where('name', 'Третья')->first();

        // Директор - доступны все категории
        $director->comfortCategories()->attach([
            $firstCategory->id,
            $secondCategory->id,
            $thirdCategory->id,
        ]);

        // Менеджер - доступны первая и вторая категории
        $manager->comfortCategories()->attach([
            $firstCategory->id,
            $secondCategory->id,
        ]);

        // Сотрудник - доступна только третья категория
        $employee->comfortCategories()->attach([
            $thirdCategory->id,
        ]);
    }
}
