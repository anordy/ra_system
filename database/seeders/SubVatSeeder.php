<?php

namespace Database\Seeders;

use App\Enum\SubVatConstant;
use App\Models\Returns\Vat\SubVat;
use Illuminate\Database\Seeder;

class SubVatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subVats = SubVatConstant::getConstants();

        foreach ($subVats as $key => $value) {
            SubVat::updateOrCreate([
                'is_approved' => 1, 
                'gfs_code' => '104395', 
                'name' => $value, 
                'code' => $key
            ]);
        }
    }
}
