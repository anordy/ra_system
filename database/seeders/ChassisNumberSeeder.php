<?php

namespace Database\Seeders;

use App\Enum\GeneralConstant;
use App\Models\Tra\ChassisNumber;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ChassisNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cars = [
            ['make' => 'BMW', 'model_type' => ['X1', 'X3', 'X5', 'X6'], 'body_type' => 'SUV'],
            ['make' => 'Toyota', 'model_type' => ['IST', 'Passo', 'Rumio', 'Premio'], 'body_type' => 'Sedan'],
        ];

        for ($i = 0; $i < 50; $i++) {
            $faker = Factory::create();

            $car = $faker->randomElements($cars)[0];

            ChassisNumber::create([
                'importer_name' => $faker->name,
                'importer_tin' => random_int(100000000, 999999999),
                'chassis_number' => 'LPA' .random_int(100000000, 999999999),
                'make' => $car['make'],
                'year' => $faker->randomElements(['2007', '2010', '2013'])[0],
                'model_number' => random_int(100000000, 999999999),
                'model_type' => $faker->randomElements($car['model_type'])[0],
                'body_type' => $car['body_type'],
                'transmission_type' => $faker->randomElements(['Automatic', 'Manual'])[0],
                'vehicle_category' => $faker->randomElements(['Private', 'Public'])[0],
                'tare_weight' => $faker->randomElements([2000, 3000, 2500, 1400])[0],
                'gross_weight' => $faker->randomElements([2000, 3000, 2500, 1400])[0],
                'engine_number' => random_int(100000000, 999999999),
                'passenger_capacity' => $faker->randomElements([4,5,6])[0],
                'purchase_day' => Carbon::today(),
                'color' => $faker->colorName,
                'fuel_type' => $faker->randomElements(['Petrol', 'Diesel'])[0],
                'owner_category' => $faker->randomElements(['Private', 'Public', 'Government'])[0],
                'usage_type' => $faker->randomElements(['Private', 'Commercial', 'Government'])[0],
                'imported_from' => $faker->randomElements(['Japan', 'Singapore', 'South Africa'])[0],
                'tansad_number' => random_int(100000000, 999999999),
                'status' => GeneralConstant::CHASSIS_REGISTRATION,
                'engine_cubic_capacity' => $faker->randomElements([1990, 2300, 2500])[0]
            ]);
        }
    }
}
