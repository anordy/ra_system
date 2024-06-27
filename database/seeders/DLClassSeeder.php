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
                'description' => 'Motorcycle'
            ],
            [
                'name' => 'B',
                'description' => 'Private Car > 7 seats'
            ],
            [
                'name' => 'B1',
                'description' => 'Private Car <= 7 seats'
            ],
            [
                'name' => 'C',
                'description' => 'Goods Vehicle > 3.5 Tons'
            ],
            [
                'name' => 'C1',
                'description' => 'Goods Vehicle <= 3.5 Tons'
            ],
            [
                'name' => 'D',
                'description' => 'Public Vehicle > 7 seats'
            ],
            [
                'name' => 'D1',
                'description' => 'Public Vehicle <= 7 seats'
            ],
            [
                'name' => 'E',
                'description' => 'Tractors'
            ],
            [
                'name' => 'G',
                'description' => 'General (All Vehicles)'
            ],
            [
                'name' => 'M',
                'description' => 'Moped'
            ],
            [
                'name' => 'N',
                'description' => 'None'
            ]
        ];
        foreach ($classes as $cl){
            DlLicenseClass::updateOrCreate($cl);
        }
    }
}
