<?php

namespace Database\Seeders;

use App\Models\DlRestriction;
use Illuminate\Database\Seeder;

class DlRestrictionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restrictions = [
            ['code' => 1, 'description' => 'Corrective Lenses', 'symbol' => 'A'],
            ['code' => 2, 'description' => 'Daylight Driving Only', 'symbol' => 'B'],
            ['code' => 3, 'description' => 'Automatic trans/Power Steering', 'symbol' => 'C'],
            ['code' => 4, 'description' => 'Extra Seat Cushion', 'symbol' => 'D'],
            ['code' => 5, 'description' => 'Restricted ot 50 km/hour', 'symbol' => 'E'],
            ['code' => 6, 'description' => '40 Kilometer radius', 'symbol' => 'F'],
            ['code' => 7, 'description' => 'Special Hand devices', 'symbol' => 'G'],
            ['code' => 8, 'description' => 'Intermediate License', 'symbol' => 'H'],
            ['code' => 9, 'description' => 'Extension on foot device', 'symbol' => 'I'],
            ['code' => 10, 'description' => 'Leg Braces', 'symbol' => 'J'],
            ['code' => 11, 'description' => 'Steering column accelerator', 'symbol' => 'L'],
            ['code' => 13, 'description' => '3 wheel motorcycle only', 'symbol' => 'M'],
            ['code' => 17, 'description' => 'Hearing Auditory Assistance', 'symbol' => 'N'],
        ];

        foreach ($restrictions as $restriction) {
            DlRestriction::updateOrCreate($restriction);
        }
    }
}
