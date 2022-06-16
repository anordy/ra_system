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
        ];
        foreach ($data as $row) {
            Permission::updateOrCreate($row);
        }
    }
}
