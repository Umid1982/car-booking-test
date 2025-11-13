<?php

namespace Database\Seeders;

use App\Models\ComfortCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComfortCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Первая', 'level' => 1],
            ['name' => 'Вторая', 'level' => 2],
            ['name' => 'Третья', 'level' => 3],
        ];

        foreach ($categories as $category) {
            ComfortCategory::create($category);
        }
    }
}
