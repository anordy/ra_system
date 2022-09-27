<?php

namespace Database\Seeders;

use App\Models\DlClassAdditionFee;
use Illuminate\Database\Seeder;

class DlClassAdditionFeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DlClassAdditionFee::query()->updateOrCreate(['amount'=>15000,'name'=>'License Class Addition']);
    }
}
