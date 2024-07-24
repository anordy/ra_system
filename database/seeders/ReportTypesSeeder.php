<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\Permission;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (GeneralReportType::getConstants() as $type) {
            $permissionName = 'view-'. strtolower(str_replace(' ', '-', $type));
            Permission::updateOrCreate( ['name' => $permissionName, 'sys_module_id' => 35]);
            ReportType::updateOrCreate(['name' => $type], ['permission' => $permissionName]);
        }
    }
}