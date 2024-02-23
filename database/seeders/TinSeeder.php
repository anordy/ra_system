<?php

namespace Database\Seeders;

use App\Models\Tra\Tin;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $faker = Factory::create();

            Tin::create([
                'tin' => random_int(100000000, 999999999),
                'date_of_birth' => $faker->dateTimeBetween('1970-01-01', '2003-01-01'),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'gender' => $faker->randomElements(['M', 'F'])[0],
                'taxpayer_name' => $faker->company,
                'email' => $faker->email,
                'is_business_tin' => $faker->randomElements([0, 1])[0],
                'is_entity_tin' => $faker->randomElements([0, 1])[0],
                'mobile' => '0743' . random_int(100000, 999999),
                'registration_date' => Carbon::today(),
                'status' => 'VERIFIED'
            ]);
        }
    }
}
