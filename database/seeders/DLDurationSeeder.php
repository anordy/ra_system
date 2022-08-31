<?php

namespace Database\Seeders;

use App\Models\DlLicenseDuration;
use Illuminate\Database\Seeder;

class DLDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DlLicenseDuration::updateOrCreate(['number_of_years' => 3, 'description' => 'Three Years']);
        DlLicenseDuration::updateOrCreate(['number_of_years' => 5, 'description' => 'Three Years']);
    }
}
