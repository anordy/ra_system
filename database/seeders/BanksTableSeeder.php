<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create(['name' => 'CRDB']);
        Bank::create(['name' => 'NMB']);
        Bank::create(['name' => 'PBZ']);
        Bank::create(['name' => 'EXIM Bank']);
        Bank::create(['name' => 'UBA']);
        Bank::create(['name' => 'Azania Bank']);
        Bank::create(['name' => 'DTB']);
        Bank::create(['name' => 'Equity Bank']);
    }
}
