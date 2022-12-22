<?php

namespace Database\Seeders;

use App\Models\ApprovalLevel;
use Illuminate\Database\Seeder;

class ApprovalLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['name' => 'Maker', 'level'=> 'Level one', 'details' => 'The one responsible for making changes'],
            ['name' => 'Checker', 'level'=> 'Level two', 'details' => 'The one responsible for checking the changes made'],
        ];

        foreach ($data as $row) {
            ApprovalLevel::updateOrCreate($row);
        }
    }
}
