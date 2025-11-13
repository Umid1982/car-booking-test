<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            ['name' => 'Иван Иванов', 'phone' => '+79990000001', 'license_number' => 'AB123456'],
            ['name' => 'Петр Петров', 'phone' => '+79990000002', 'license_number' => 'CD789012'],
            ['name' => 'Сергей Сергеев', 'phone' => '+79990000003', 'license_number' => 'EF345678'],
            ['name' => 'Алексей Алексеев', 'phone' => '+79990000004', 'license_number' => 'GH901234'],
            ['name' => 'Дмитрий Дмитриев', 'phone' => '+79990000005', 'license_number' => 'IJ567890'],
            ['name' => 'Андрей Андреев', 'phone' => '+79990000006', 'license_number' => 'KL123456'],
            ['name' => 'Николай Николаев', 'phone' => '+79990000007', 'license_number' => 'MN789012'],
            ['name' => 'Владимир Владимиров', 'phone' => '+79990000008', 'license_number' => 'OP345678'],
            ['name' => 'Михаил Михайлов', 'phone' => '+79990000009', 'license_number' => 'QR901234'],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
