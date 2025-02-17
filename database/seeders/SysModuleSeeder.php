<?php

namespace Database\Seeders;

use App\Models\SysModule;
use Illuminate\Database\Seeder;

class SysModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'code' => 'revenue-management', 'name' => 'Revenue Management'],
        ];
        foreach ($data as $row) {
            SysModule::updateOrCreate($row);
        }
    }
}
