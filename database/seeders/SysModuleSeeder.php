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
            ['name' => 'Configurations'],
            ['name' => 'WithholdingAgents'],
            ['name' => 'BusinessManagement'],
            ['name' => 'ChangeTaxType'],
        ];
        foreach ($data as $row) {
            SysModule::updateOrCreate($row);
        }
    }
}
