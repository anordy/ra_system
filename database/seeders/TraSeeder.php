<?php

namespace Database\Seeders;

use Database\Seeders\Tra\VehicleBodyTypeSeeder;
use Database\Seeders\Tra\VehicleCategorySeeder;
use Database\Seeders\Tra\VehicleColorSeeder;
use Database\Seeders\Tra\VehicleFuelTypeSeeder;
use Database\Seeders\Tra\VehicleMakeSeeder;
use Database\Seeders\Tra\VehicleModelNumberSeeder;
use Database\Seeders\Tra\VehicleModelTypeSeeder;
use Database\Seeders\Tra\VehicleOwnerCategorySeeder;
use Database\Seeders\Tra\VehicleTransmissionTypeSeeder;
use Database\Seeders\Tra\VehicleUsageTypeSeeder;
use Illuminate\Database\Seeder;

class TraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VehicleBodyTypeSeeder::class);
        $this->call(VehicleCategorySeeder::class);
        $this->call(VehicleMakeSeeder::class);
        $this->call(VehicleModelTypeSeeder::class);
        $this->call(VehicleModelNumberSeeder::class);
        $this->call(VehicleColorSeeder::class);
        $this->call(VehicleFuelTypeSeeder::class);
        $this->call(VehicleTransmissionTypeSeeder::class);
        $this->call(VehicleUsageTypeSeeder::class);
        $this->call(VehicleOwnerCategorySeeder::class);
    }
}
