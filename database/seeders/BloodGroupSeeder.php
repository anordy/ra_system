<?php

namespace Database\Seeders;

use App\Models\DlBloodGroup;
use Illuminate\Database\Seeder;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = ['A', 'B', 'AB', 'O'];
        foreach ($groups as $group){
            DlBloodGroup::create(['name' => "{$group}+"]);
            DlBloodGroup::create(['name' => "{$group}-"]);
        }
    }
}
