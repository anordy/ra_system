<?php

namespace Database\Seeders;

use App\Models\TransactionFee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class TransactionFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 0.00, 'maximum_amount' => 10000, 'fee' => 0.025]);
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 100001, 'maximum_amount' => 500000, 'fee' => 0.02]);
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 500001, 'maximum_amount' => 1000000, 'fee' => 0.013]);
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 1000001, 'maximum_amount' => 5000000, 'fee' => 0.003]);
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 5000001, 'maximum_amount' => 10000000, 'fee' => 0.0015]);
        TransactionFee::updateOrCreate(['created_by' => 1, 'minimum_amount' => 10000001, 'maximum_amount' => null, 'fee' => 20000]);
    }
}
