<?php

namespace Database\Seeders;

use App\Models\BankChannel;
use Illuminate\Database\Seeder;

class BankChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Internet Banking', 'description' => 'Online banking access through web platforms.'],
            ['name' => 'Agency Banking', 'description' => 'Banking services provided through third-party agents.'],
            ['name' => 'SIM Banking', 'description' => 'Banking transactions via mobile SIM cards and USSD.'],
            ['name' => 'Mobile Banking', 'description' => 'Banking services via mobile applications.'],
            ['name' => 'ATM', 'description' => 'Cash withdrawal and other banking services via ATMs.'],
            ['name' => 'POS', 'description' => 'Point of Sale transactions using bank cards.'],
        ];

        foreach ($data as $row) {
            BankChannel::updateOrCreate($row);
        }
    }
}
