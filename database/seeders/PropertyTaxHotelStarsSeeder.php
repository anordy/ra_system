<?php

namespace Database\Seeders;

use App\Models\HotelStar;
use App\Models\PropertyTax\PropertyTaxHotelStar;
use Illuminate\Database\Seeder;

class PropertyTaxHotelStarsSeeder extends Seeder
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
            ['name' => '1 Star', 'no_of_stars' => 1, 'amount_charged' => 100000, 'currency_id' => 1, 'is_approved' => 1],
            ['name' => '2 Star', 'no_of_stars' => 2, 'amount_charged' => 200000, 'currency_id' => 1, 'is_approved' => 1],
            ['name' => '3 Star', 'no_of_stars' => 3, 'amount_charged' => 300000, 'currency_id' => 1, 'is_approved' => 1],
            ['name' => '4 Star', 'no_of_stars' => 4, 'amount_charged' => 400000, 'currency_id' => 1, 'is_approved' => 1],
            ['name' => '5 Star', 'no_of_stars' => 5, 'amount_charged' => 500000, 'currency_id' => 1, 'is_approved' => 1],
            ['name' => 'Others', 'no_of_stars' => 0, 'amount_charged' => 50000, 'currency_id' => 1, 'is_approved' => 1],
        ];

        foreach ($hotelStars as $hotelStar) {
            PropertyTaxHotelStar::updateOrCreate(
                [
                    'name' => $hotelStar['name']
                ],
                $hotelStar
            );
        }
    }
}
