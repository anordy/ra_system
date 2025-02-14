<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MvrSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MvrTemporaryTransportFileTypeSeeder::class);
        $this->call(MvrTransferReasonTableSeeder::class);
        $this->call(MvrPlateNumberTypeSeeder::class);
        $this->call(WorkflowMvrBlackListSeeder::class);
        $this->call(WorkflowMvrRegistrationSeeder::class);
        $this->call(WorkflowDrivingLicenseApplicationSeeder::class);
        $this->call(DLDurationSeeder::class);
        $this->call(DLClassSeeder::class);
        $this->call(MvrRegistrationTypeCategoriesSeeder::class);
        $this->call(MvrRegistrationTypesSeeder::class);
        $this->call(MvrClassesTableSeeder::class);
        $this->call(MvrPlateNumberColorsTableSeeder::class);
        $this->call(DesignationsSeeder::class);
        $this->call(TraSeeder::class);
    }
}
