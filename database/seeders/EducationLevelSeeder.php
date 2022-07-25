<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = ['Master Degree', 'Degree', 'Advanced Diploma',
            'Diploma', 'Basic Certificate', 'Advanced Level (ASCE)',
            'Ordinary Level (CSE)'];
        foreach ($levels as $level) {
            EducationLevel::updateOrCreate([
                'name' => $level
            ]);
        }

    }
}
