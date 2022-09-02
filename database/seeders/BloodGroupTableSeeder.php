<?php

namespace Database\Seeders;

use App\Models\DlBloodGroup;
use Illuminate\Database\Seeder;

class BloodGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DlBloodGroup::updateOrCreate([
            'name' => 'A+',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'A-',
        ]);

        DlBloodGroup::updateOrCreate([
            'name' => 'B+',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'B+',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'AB+',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'AB-',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'O+',
        ]);
        DlBloodGroup::updateOrCreate([
            'name' => 'O-',
        ]);

    }
}
