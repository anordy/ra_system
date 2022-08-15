<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Relief\ReliefProject;

class ReliefProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ReliefProject::create([
            "name"=>"NGOs",
            "description"=>"Taasisi isizo ya Kiserikali",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"Taasisi ya kibalozi ",
            "description"=>"Taasisi za kibalozi ",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"Shirika la umoja wa mataifa",
            "description"=>"Taasisi za mashirika ya umoja wa mataifa",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"Ufadhili na mashirika ya wahisani",
            "description"=>"Miradi chini ya ufadhili na mashirika ya wahisani",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"ZIPA",
            "description"=>"Miradi iliyosajiliwa na ZIPA",
            "created_by"=>1,
        ]);
    }
}
