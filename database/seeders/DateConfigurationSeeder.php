<?php

namespace Database\Seeders;

use App\Models\DateConfiguration;
use Illuminate\Database\Seeder;

class DateConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DateConfiguration::updateOrCreate([
            'name' => 'Valid days of financial month',
            'code' => 'validMonthDays',
            'value' => 30
        ]);

        DateConfiguration::updateOrCreate([
            'name' => 'LandLease: Months before review (i.e. prevent over a year in advance payment)',
            'code' => 'MonthsBeforeReview',
            'value' => 12
        ]);
    }
}
