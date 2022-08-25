<?php

namespace Database\Seeders;

use App\Models\Debts\RecoveryMeasureCategory;
use Illuminate\Database\Seeder;

class RecoveryMeasureCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RecoveryMeasureCategory::updateOrCreate(['code' => 'PTP','name' => 'Payment by Third party']);
        RecoveryMeasureCategory::updateOrCreate(['code' => 'COB','name' => 'Closure of business']);
        RecoveryMeasureCategory::updateOrCreate(['code' => 'DA','name' => 'Distress action']);
        RecoveryMeasureCategory::updateOrCreate(['code' => 'SOG','name' => 'Seizure of goods']);
        RecoveryMeasureCategory::updateOrCreate(['code' => 'CA','name' => 'Court action']);

    }
}
