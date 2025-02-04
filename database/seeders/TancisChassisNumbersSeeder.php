<?php

namespace Database\Seeders;

use App\Models\Tra\TancisChassisNumber;
use App\Models\Tra\TraVehicleBodyType;
use App\Models\Tra\TraVehicleCategory;
use App\Models\Tra\TraVehicleColor;
use App\Models\Tra\TraVehicleFuelType;
use App\Models\Tra\TraVehicleMake;
use App\Models\Tra\TraVehicleModelNumber;
use App\Models\Tra\TraVehicleModelType;
use App\Models\Tra\TraVehicleOwnerCategory;
use App\Models\Tra\TraVehicleTransmissionType;
use App\Models\Tra\TraVehicleUsageType;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TancisChassisNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 200; $i++) {
            $modelNumber = TraVehicleModelNumber::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $make = TraVehicleMake::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $modelType = TraVehicleModelType::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $bodyType = TraVehicleBodyType::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $transmission = TraVehicleTransmissionType::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $color = TraVehicleColor::class::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $category = TraVehicleCategory::class::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $ownerCategory = TraVehicleOwnerCategory::class::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $fuelType = TraVehicleFuelType::class::orderByRaw('DBMS_RANDOM.VALUE')->first();
            $uageType = TraVehicleUsageType::class::orderByRaw('DBMS_RANDOM.VALUE')->first();

            $vehicle = TancisChassisNumber::updateOrCreate([
                'chassis_number' => 'LXAPCK4A9LC000'.$i
            ], [
                'chassis_number' => 'LXAPCK4A9LC000'.$i,
                'tansad_number' => \Faker\Factory::create()->numerify('##########'),
                'importer_tin' => \Faker\Factory::create()->numerify('##########'),
                'importer_name' => \Faker\Factory::create()->company,
                'make' => $make->code,
                'model_number' => $modelNumber->code,
                'model_type' => $modelType->code,
                'body_type' => $bodyType->code,
                'transmission_type' => $transmission->code,
                'vehicle_category' => $category->code,
                'tare_weight' =>  \Faker\Factory::create()->numerify('####'),
                'gross_weight' =>  \Faker\Factory::create()->numerify('####'),
                'engine_number' =>  \Faker\Factory::create()->numerify('##########'),
                'engine_capacity' =>  \Faker\Factory::create()->numerify('####'),
                'passenger_capacity' =>  \Faker\Factory::create()->numerify('#'),
                'purchase_day' => '2025-01-01',
                'vehicle_manufacture_year' => Factory::create()->year,
                'vehicle_color' => $color->code,
                'fuel_type' => $fuelType->code,
                'owner_category' => $ownerCategory->code,
                'usage_type' => $uageType->code,
                'imported_from' => Factory::create()->country,
            ]);
        }


    }
}
