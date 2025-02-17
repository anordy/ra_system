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

            # KYC Data
            ['name' => 'taxpayer_view', 'sys_module_id' => 1],
            
        ];

        foreach ($data as $row) {
            Permission::updateOrCreate(
                ['name' => $row['name']],
                ['sys_module_id' => $row['sys_module_id']]
            );
        }
    }
}
