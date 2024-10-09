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
            ['name' => '1 Star', 'no_of_stars' => 1, 'infrastructure_charged' => 2, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => '2 Star', 'no_of_stars' => 2, 'infrastructure_charged' => 4, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => '3 Star', 'no_of_stars' => 3, 'infrastructure_charged' => 4, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => '4 Star', 'no_of_stars' => 4, 'infrastructure_charged' => 5, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => '5 Star', 'no_of_stars' => 5, 'infrastructure_charged' => 5, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => 'A', 'no_of_stars' => null, 'infrastructure_charged' => 2, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => 'AA', 'no_of_stars' => null, 'infrastructure_charged' => 2, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => 'Others', 'no_of_stars' => null, 'infrastructure_charged' => 2, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => 'Under the Ocean', 'no_of_stars' => null, 'infrastructure_charged' => 10, 'currency_id' => 2, 'is_approved' => 1],
            ['name' => 'Small Island', 'no_of_stars' => null, 'infrastructure_charged' => 10, 'currency_id' => 2, 'is_approved' => 1],
        ];

        foreach ($hotelStars as $hotelStar) {
            HotelStar::updateOrCreate(
                [
                    'name' => $hotelStar['name']
                ],
                $hotelStar
            );
        }
    }
}
