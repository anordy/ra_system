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
            ['name' => 'Administrator', 'is_approved' => 1],
            ['name' => 'Head Of Department', 'is_approved' => 1],
            ['name' => 'Senior Manager','report_to' => 2, 'is_approved' => 1],
            ['name' => 'Senior Specialist', 'report_to' => 3, 'is_approved' => 1],
            ['name' => 'Specialist', 'report_to' => 4, 'is_approved' => 1],
        ];

        foreach ($data as $row) {
            Role::updateOrCreate($row);
        }

        $role = Role::where('name', 'Administrator')->first();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions);
    }
}
