<?php

namespace Database\Seeders;

use App\Models\DlLicenseClass;
use Illuminate\Database\Seeder;

class DLClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = ['A', 'A1', 'A2', 'A3', 'B', 'C', 'C1', 'C2', 'C3', 'D', 'E', 'F', 'G', 'H'];

        foreach ($classes as $cl){
            DlLicenseClass::create(['name' => $cl]);
        }
    }
}
