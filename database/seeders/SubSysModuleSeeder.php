<?php

namespace Database\Seeders;

use App\Models\SubSysModule;
use App\Models\SysModule;
use Illuminate\Database\Seeder;

class SubSysModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'code' => 'users', 'name' => 'Users', 'sysmodule_id'=>18],
            ['id' => 2, 'code' => 'roles', 'name' => 'Roles', 'sysmodule_id'=>18],
            ['id' => 3, 'code' => 'countries', 'name' => 'Countries', 'sysmodule_id'=>18],
            ['id' => 4, 'code' => 'region', 'name' => 'Region', 'sysmodule_id'=>18],
            ['id' => 5, 'code' => 'district', 'name' => 'District', 'sysmodule_id'=>18],
            ['id' => 6, 'code' => 'ward', 'name' => 'Ward', 'sysmodule_id'=>18],
            ['id' => 7, 'code' => 'streets', 'name' => 'Streets', 'sysmodule_id'=>18],
            ['id' => 8, 'code' => 'exchange-rate', 'name' => 'Exchange rate', 'sysmodule_id'=>18],
            ['id' => 9, 'code' => 'interest-rate', 'name' => 'Interest rate', 'sysmodule_id'=>18],
            ['id' => 10, 'code' => 'penalty-rate', 'name' => 'Penalty rate', 'sysmodule_id'=>18],
            ['id' => 11, 'code' => 'education-level', 'name' => 'Education level', 'sysmodule_id'=>18],
            ['id' => 12, 'code' => 'tax-types', 'name' => 'Tax types', 'sysmodule_id'=>18],
            ['id' => 13, 'code' => 'business-files', 'name' => 'Business files', 'sysmodule_id'=>18],
            ['id' => 14, 'code' => 'tax-regions', 'name' => 'Tax regions', 'sysmodule_id'=>18],
            ['id' => 15, 'code' => 'financial-years', 'name' => 'Financial years', 'sysmodule_id'=>18],
            ['id' => 16, 'code' => 'financial-months', 'name' => 'Financial months', 'sysmodule_id'=>18],
            ['id' => 17, 'code' => 'return-configuration', 'name' => 'Return configuaration', 'sysmodule_id'=>18],
            ['id' => 18, 'code' => 'transaction-fees', 'name' => 'Transaction fees', 'sysmodule_id'=>18],
            ['id' => 19, 'code' => 'tax-consultant-fees', 'name' => 'Tax consultant fees', 'sysmodule_id'=>18],
            ['id' => 20, 'code' => 'system-setting-categories', 'name' => 'System setting categories', 'sysmodule_id'=>18],
            ['id' => 21, 'code' => 'system-settings', 'name' => 'System settings', 'sysmodule_id'=>18],
            ['id' => 22, 'code' => 'zrb-bank-accounts', 'name' => 'ZRA bank accounts', 'sysmodule_id'=>18],

        ];
        foreach ($data as $row) {
            SubSysModule::updateOrCreate($row);
        }
    }
}
