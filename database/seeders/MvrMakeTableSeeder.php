<?php

namespace Database\Seeders;

use App\Models\MvrMake;
use Illuminate\Database\Seeder;

class MvrMakeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrMake::query()->updateOrcreate(['name' => 'Toyota','id'=>1]);
        MvrMake::query()->updateOrcreate(['name' => 'Subaru','id'=>2]);
        MvrMake::query()->updateOrcreate(['name' => 'Nissan','id'=>3]);
    }
}
