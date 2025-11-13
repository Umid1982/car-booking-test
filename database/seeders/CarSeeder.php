<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = Driver::all();
        $models = CarModel::all();

        $cars = [
            // Первая категория
            ['car_model_id' => $models->where('name', 'S-Class')->first()->id, 'driver_id' => $drivers[0]->id, 'license_plate' => 'A001AA', 'year' => 2023, 'color' => 'Black', 'status' => 'available'],
            ['car_model_id' => $models->where('name', '7 Series')->first()->id, 'driver_id' => $drivers[1]->id, 'license_plate' => 'A002AA', 'year' => 2022, 'color' => 'White', 'status' => 'available'],
            ['car_model_id' => $models->where('name', 'A8')->first()->id, 'driver_id' => $drivers[2]->id, 'license_plate' => 'A003AA', 'year' => 2023, 'color' => 'Silver', 'status' => 'maintenance'],

            // Вторая категория
            ['car_model_id' => $models->where('name', 'Camry')->first()->id, 'driver_id' => $drivers[3]->id, 'license_plate' => 'B001BB', 'year' => 2022, 'color' => 'Blue', 'status' => 'available'],
            ['car_model_id' => $models->where('name', 'Accord')->first()->id, 'driver_id' => $drivers[4]->id, 'license_plate' => 'B002BB', 'year' => 2021, 'color' => 'Red', 'status' => 'available'],
            ['car_model_id' => $models->where('name', 'Passat')->first()->id, 'driver_id' => $drivers[5]->id, 'license_plate' => 'B003BB', 'year' => 2023, 'color' => 'Gray', 'status' => 'available'],

            // Третья категория
            ['car_model_id' => $models->where('name', 'Granta')->first()->id, 'driver_id' => $drivers[6]->id, 'license_plate' => 'C001CC', 'year' => 2021, 'color' => 'White', 'status' => 'available'],
            ['car_model_id' => $models->where('name', 'Logan')->first()->id, 'driver_id' => $drivers[7]->id, 'license_plate' => 'C002CC', 'year' => 2022, 'color' => 'Black', 'status' => 'available'],
            ['car_model_id' => $models->where('name', 'Solaris')->first()->id, 'driver_id' => $drivers[8]->id, 'license_plate' => 'C003CC', 'year' => 2023, 'color' => 'Silver', 'status' => 'available'],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
