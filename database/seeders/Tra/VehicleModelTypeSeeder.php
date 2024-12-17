<?php

namespace Database\Seeders\Tra;

use App\Imports\TraVehicleInformationImport;
use Illuminate\Database\Seeder;

class VehicleModelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $import = new TraVehicleInformationImport();
        $import->import(public_path('imports/TRA_VEHICLE_INFO.xlsx'));

    }
}
