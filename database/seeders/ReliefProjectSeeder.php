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
        ReliefProject::create([
            "name"=>"NGOs",
            "description"=>"Non-Governmental Organization",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"Consular institutions",
            "description"=>"Consular institutions",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"United Nations Organizations",
            "description"=>"Taasisi za mashirika ya umoja wa mataifa",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"Funding and charitable organizations",
            "description"=>"Projects under the sponsorship of charitable organizations",
            "created_by"=>1,
        ]);
        ReliefProject::create([
            "name"=>"ZIPA",
            "description"=>"Projects registered with ZIPA",
            "created_by"=>1,
        ]);
    }
}
