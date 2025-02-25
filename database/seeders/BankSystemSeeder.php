<?php

namespace Database\Seeders;

use App\Models\BankSystem;
use App\Models\System;
use Illuminate\Database\Seeder;

class BankSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Savvy', 'description' => 'Online banking access through web platforms.'],
            ['name' => 'CSM', 'description' => 'Banking services provided through third-party agents.'],
            ['name' => 'FBE Transaction', 'description' => 'Banking transactions via mobile SIM cards and USSD.'],
            ['name' => 'Optima', 'description' => 'Banking services via mobile applications.']
        ];

        foreach ($data as $row) {
            BankSystem::updateOrCreate($row);
        }
    }
}
