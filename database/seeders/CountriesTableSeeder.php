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
            'code' => 'TZ',
            'is_approved' => 1
        ]);

        Country::updateOrCreate([
            'name' => 'Kenya',
            'nationality' => 'Kenyan',
            'code' => 'KE',
            'is_approved' => 1
        ]);

        Country::updateOrCreate([
            'name' => 'Uganda',
            'nationality' => 'Ugandan',
            'code' => 'UG',
            'is_approved' => 1
        ]);

        Country::updateOrCreate([
            'name' => 'Japan',
            'nationality' => 'Japanese',
            'code' => 'JP',
            'is_approved' => 1
        ]);
    }
}
