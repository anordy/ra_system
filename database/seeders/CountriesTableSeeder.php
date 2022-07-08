<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Country::updateOrcreate([
            'name' => 'Tanzania',
            'nationality' => 'Tanzanian',
            'code' => 'TZ'
        ]);

        Country::updateOrCreate([
            'name' => 'Kenya',
            'nationality' => 'Kenyan',
            'code' => 'KE'
        ]);

        Country::updateOrCreate([
            'name' => 'Uganda',
            'nationality' => 'Ugandan',
            'code' => 'UG'
        ]);
    }
}
