<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleCategories = [
            '01' => 'Motorcycle (Less Than 3 Wheels)',
            '02' => 'Motor Tricycle',
            '03' => 'Light Passenger Vehicle (Less Than 12 Persons)',
            '04' => 'Heavy Passenger Vehicle (12 Or More Persons)',
            '05' => 'Light Load Vehicle (Gvm 3500Kg Or Less)',
            '06' => 'Heavy Load Veh(Gvm > 3500Kg)',
            '07' => 'Trailer',
            '08' => 'Agricultural Tractor',
            '09' => 'Agricultural Trailor',
            '10' => 'Construction Equipment',
            '11' => 'Others',
        ];

        foreach ($vehicleCategories as $code =>$vehicleCategory) {
            TraVehicleCategory::updateOrCreate([
                'code' => $code
            ], [
                'code' => $code,
                'name' => $vehicleCategory
            ]);
        }
    }
}
