<?php

namespace Database\Seeders;

use App\Models\BankChannel;
use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'High'],
            ['name' => 'Medium'],
            ['name' => 'Low'],
        ];

        foreach ($data as $row) {
            Priority::updateOrCreate($row);
        }
    }
}
