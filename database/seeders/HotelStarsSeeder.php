<?php

namespace Database\Seeders;

use App\Models\HotelStar;
use Illuminate\Database\Seeder;

class HotelStarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hotelStars = [
            ['no_of_stars' => 1, 'infrastructure_charged' => 2, 'currency_id' => 2,],
            ['no_of_stars' => 2, 'infrastructure_charged' => 4, 'currency_id' => 2,],
            ['no_of_stars' => 3, 'infrastructure_charged' => 4, 'currency_id' => 2,],
            ['no_of_stars' => 4, 'infrastructure_charged' => 5, 'currency_id' => 2,],
            ['no_of_stars' => 5, 'infrastructure_charged' => 5, 'currency_id' => 2,],
        ];

        foreach ($hotelStars as $hotelStar) {
            HotelStar::updateOrCreate($hotelStar);
        }
    }
}
