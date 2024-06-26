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
        $durations = [
            ['number_of_years' => 2, 'description' => 'Two Years'],
            ['number_of_years' => 3, 'description' => 'Three Years'],
            ['number_of_years' => 5, 'description' => 'Five Years'],
        ];

        foreach ($durations as $duration) {
            DlLicenseDuration::updateOrCreate([
                'number_of_years' => $duration['number_of_years']
            ], $duration);
        }
    }
}
