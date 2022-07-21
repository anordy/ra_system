<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'roles_add', 'sys_module_id' => 1],

            ['name' => 'withholding_agents_add', 'sys_module_id' => 2],
            ['name' => 'withholding_agents_edit', 'sys_module_id' => 2],
            ['name' => 'withholding_agents_view', 'sys_module_id' => 2],
            ['name' => 'withholding_agents_disable', 'sys_module_id' => 2],

            ['name' => 'business_registrations_view', 'sys_module_id' => 3],

            ['name' => 'change_tax_type_view', 'sys_module_id' => 4],
        ];
        foreach ($data as $row) {
            Permission::updateOrCreate($row);
        }
    }
}
