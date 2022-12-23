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
            ['name' => 'Registration Manager', 'is_approved' => 1],
            ['name' => 'Registration Officer', 'report_to' => 2, 'is_approved' => 1],
            ['name' => 'Compliance Manager', 'is_approved' => 1],
            ['name' => 'Compliance Officer', 'report_to' => 4, 'is_approved' => 1],
            ['name' => 'Directory Of TRAI', 'is_approved' => 1],
            ['name' => 'Commissioner', 'is_approved' => 1],
            ['name' => 'Audit Manager', 'is_approved' => 1],
            ['name' => 'Debt Manager', 'is_approved' => 1],
            ['name' => 'CRDM', 'is_approved' => 1],
        ];

        foreach ($data as $row) {
            Role::updateOrCreate($row);
        }

        $role = Role::where('name', 'Administrator')->first();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions);
    }
}
