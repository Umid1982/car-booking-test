<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $cars = Car::all();

        if ($users->isEmpty() || $cars->isEmpty()) {
            return;
        }

        // Создаем несколько поездок для тестирования занятости
        // Поездка в прошлом (завершена)
        Trip::create([
            'car_id' => $cars->first()->id,
            'user_id' => $users->first()->id,
            'start_at' => now()->subDays(5),
            'end_at' => now()->subDays(4),
            'status' => 'completed',
        ]);

        // Поездка в будущем (запланирована)
        Trip::create([
            'car_id' => $cars->first()->id,
            'user_id' => $users->first()->id,
            'start_at' => now()->addDays(1),
            'end_at' => now()->addDays(2),
            'status' => 'planned',
        ]);

        // Поездка в процессе
        Trip::create([
            'car_id' => $cars->skip(1)->first()->id,
            'user_id' => $users->first()->id,
            'start_at' => now()->subHours(2),
            'end_at' => now()->addHours(2),
            'status' => 'in_progress',
        ]);
    }
}
