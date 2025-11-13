<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Заполняем базовые данные
        $this->call([
            ComfortCategorySeeder::class,
            PositionSeeder::class,
            CarModelSeeder::class,
            DriverSeeder::class,
            CarSeeder::class,
        ]);

        // Создаем тестовых пользователей с должностями
        $director = Position::where('name', 'Директор')->first();
        $manager = Position::where('name', 'Менеджер')->first();
        $employee = Position::where('name', 'Сотрудник')->first();

        // Директор
        $directorUser = User::create([
            'name' => 'Иван Директоров',
            'email' => 'director@example.com',
            'password' => Hash::make('password'),
            'position_id' => $director->id,
        ]);

        // Менеджер
        $managerUser = User::create([
            'name' => 'Петр Менеджеров',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'position_id' => $manager->id,
        ]);

        // Сотрудник
        $employeeUser = User::create([
            'name' => 'Сергей Сотрудников',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'position_id' => $employee->id,
        ]);

        // Создаем поездки для тестирования
        $this->call([
            TripSeeder::class,
        ]);
    }
}
