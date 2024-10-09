<?php

namespace Database\Seeders;

use App\Models\Config\HotelNature;
use Illuminate\Database\Seeder;

class HotelNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $natures = ['Normal', 'Small Island', 'Under the Ocean'];

        foreach ($natures as $nature) {
            HotelNature::create(['name' => $nature]);
        }
    }
}
