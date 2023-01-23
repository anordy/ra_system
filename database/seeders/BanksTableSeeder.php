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
        Bank::updateOrCreate(['name' => 'PBZ','full_name'=>'People\'s Bank of Zanzibar']);
        Bank::updateOrCreate(['name' => 'CRDB','full_name'=>'CRDB']);
        Bank::updateOrCreate(['name' => 'NMB','full_name'=>'National Microfinance Bank']);
        Bank::updateOrCreate(['name' => 'EXIM Bank','full_name'=>'EXIM Bank']);
        Bank::updateOrCreate(['name' => 'UBA','full_name'=>'United Bank of Africa']);
        Bank::updateOrCreate(['name' => 'Azania Bank','full_name'=>'Azania Bank']);
        Bank::updateOrCreate(['name' => 'DTB','full_name'=>'Diamond Trust Bank']);
        Bank::updateOrCreate(['name' => 'Equity Bank','full_name'=>'Equity Bank']);
        Bank::updateOrCreate(['name' => 'BOT','full_name'=>'Bank of Tanzania']);
    }
}
