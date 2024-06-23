<?php

namespace Database\Seeders;

use App\Models\Tra\EfdmsReceipt;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EfdmsReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {

            $exclusiveTax =  (int) (1000 * ceil(random_int(5000,1000000) / 1000));

            EfdmsReceipt::create([
                'seller_tin' => random_int(111111111,999999999),
                'receipt_number' => random_int(100000000, 999999999),
                'receipt_date' => Carbon::now(),
                'verification_code' => random_int(100000000, 999999999),
                'total_tax_exclusive' => $exclusiveTax,
                'total_tax_inclusive' => $exclusiveTax / 0.18,
                'is_cancelled' => 0,
                'is_onhold' => 0,
            ]);
        }

    }
}
