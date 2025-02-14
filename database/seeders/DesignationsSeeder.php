<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = ['CEO', 'Head of Finance', 'Representative'];

        foreach ($designations as $designation) {
            Designation::updateOrCreate([
                'name' => $designation
            ]);
        }
    }
}
