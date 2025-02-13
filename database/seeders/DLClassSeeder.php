<?php

namespace Database\Seeders;

use App\Models\DlLicenseClass;
use Illuminate\Database\Seeder;

class DLClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            [
                'name' => 'A',
                'description' => 'Motorcycle',
                'from_age' => 18,
                'to_age' => 20,
            ],
            [
                'name' => 'B',
                'description' => 'Private Car > 7 seats',
                'from_age' => 21,
                'to_age' => 24,
            ],
            [
                'name' => 'B1',
                'description' => 'Private Car <= 7 seats',
                'from_age' => 18,
                'to_age' => 20,

            ],
            [
                'name' => 'C',
                'description' => 'Goods Vehicle > 3.5 Tons',
                'from_age' => 21,
                'to_age' => 24,

            ],
            [
                'name' => 'C1',
                'description' => 'Goods Vehicle <= 3.5 Tons',
                'from_age' => 18,
                'to_age' => 20,
            ],
            [
                'name' => 'D',
                'description' => 'Public Vehicle > 7 seats',
                'from_age' => 25,
                'to_age' => 120,
            ],
            [
                'name' => 'D1',
                'description' => 'Public Vehicle <= 7 seats',
                'from_age' => 21,
                'to_age' => 24,
            ],
            [
                'name' => 'E',
                'description' => 'Tractors',
                'from_age' => 21,
                'to_age' => 24,
            ],
            [
                'name' => 'G',
                'description' => 'General (All Vehicles)',
                'from_age' => 0,
                'to_age' => 0,
            ],
            [
                'name' => 'M',
                'description' => 'Moped',
                'from_age' => 16,
                'to_age' => 17,
            ],
        ];
        foreach ($classes as $cl){
            DlLicenseClass::updateOrCreate(['name' => $cl['name']], $cl);
        }
    }
}
