<?php

namespace Database\Seeders;

use App\Models\VatReturn\VatService;
use Illuminate\Database\Seeder;

class VatServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VatService::query()->updateOrCreate([
            'name' => 'Supplies of Goods & Services',
            'code' => 'SUP'
        ]);

        VatService::query()->updateOrCreate([
            'name' => 'Purchases(Inputs)',
            'code' => 'PUR'
        ]);
    }
}
