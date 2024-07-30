<?php

namespace Database\Seeders;

use App\Models\WithholdingAgentResponsibleTitle;
use Illuminate\Database\Seeder;

class WithholdingAgentResponsibleTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = WithholdingAgentResponsibleTitle::TITLES;

        foreach ($titles as $title) {
            WithholdingAgentResponsibleTitle::updateOrCreate([
                'name' => $title
            ], [
                'name' => $title
            ]);
        }
    }
}
