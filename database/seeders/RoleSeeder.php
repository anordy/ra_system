<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['name' => 'Administrator'],
            ['name' => 'Registration Manager'],
            ['name' => 'Registration Officer', 'report_to' => 2],
            ['name' => 'Compliance Manager'],
            ['name' => 'Compliance Officer', 'report_to' => 4],
            ['name' => 'Directory Of TRAI'],
            ['name' => 'Commissioner'],
        ];
        foreach ($data as $row) {
            Role::updateOrCreate($row);
        }

        $role = Role::where('name', 'Administrator')->first();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions);
    }
}
