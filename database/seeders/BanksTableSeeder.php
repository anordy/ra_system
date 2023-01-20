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
        Bank::updateOrCreate(['name' => 'PBZ']);
        Bank::updateOrCreate(['name' => 'CRDB']);
        Bank::updateOrCreate(['name' => 'NMB']);
        Bank::updateOrCreate(['name' => 'EXIM Bank']);
        Bank::updateOrCreate(['name' => 'UBA']);
        Bank::updateOrCreate(['name' => 'Azania Bank']);
        Bank::updateOrCreate(['name' => 'DTB']);
        Bank::updateOrCreate(['name' => 'Equity Bank']);
        Bank::updateOrCreate(['name' => 'BOT']);
        Bank::updateOrCreate(['name' => 'NBC']);
    }
}
