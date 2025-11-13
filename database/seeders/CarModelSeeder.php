<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\ComfortCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstCategory = ComfortCategory::where('name', 'Первая')->first();
        $secondCategory = ComfortCategory::where('name', 'Вторая')->first();
        $thirdCategory = ComfortCategory::where('name', 'Третья')->first();

        $models = [
            // Первая категория
            ['brand' => 'Mercedes-Benz', 'name' => 'S-Class', 'comfort_category_id' => $firstCategory->id],
            ['brand' => 'BMW', 'name' => '7 Series', 'comfort_category_id' => $firstCategory->id],
            ['brand' => 'Audi', 'name' => 'A8', 'comfort_category_id' => $firstCategory->id],

            // Вторая категория
            ['brand' => 'Toyota', 'name' => 'Camry', 'comfort_category_id' => $secondCategory->id],
            ['brand' => 'Honda', 'name' => 'Accord', 'comfort_category_id' => $secondCategory->id],
            ['brand' => 'Volkswagen', 'name' => 'Passat', 'comfort_category_id' => $secondCategory->id],

            // Третья категория
            ['brand' => 'Lada', 'name' => 'Granta', 'comfort_category_id' => $thirdCategory->id],
            ['brand' => 'Renault', 'name' => 'Logan', 'comfort_category_id' => $thirdCategory->id],
            ['brand' => 'Hyundai', 'name' => 'Solaris', 'comfort_category_id' => $thirdCategory->id],
        ];

        foreach ($models as $model) {
            CarModel::create($model);
        }
    }
}
