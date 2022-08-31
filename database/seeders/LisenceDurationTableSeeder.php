<?php

namespace Database\Seeders;

use App\Models\DlLicenseDuration;
use Illuminate\Database\Seeder;

class LisenceDurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DlLicenseDuration::updateOrCreate([
            'number_of_years' => '3',
            'description' => '3 Years',
        ]);

        DlLicenseDuration::updateOrCreate([
            'number_of_years' => '5',
            'description' => '5 Years',
        ]);
    }
}
