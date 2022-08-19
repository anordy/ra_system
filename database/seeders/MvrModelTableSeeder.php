<?php

namespace Database\Seeders;

use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrModelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrModel::query()->updateOrcreate(['name' => 'Alion','mvr_make_id'=>1]);
        MvrModel::query()->updateOrcreate(['name' => 'IST','mvr_make_id'=>1]);
        MvrModel::query()->updateOrcreate(['name' => 'Prado','mvr_make_id'=>1]);
        MvrModel::query()->updateOrcreate(['name' => 'Prado','mvr_make_id'=>1]);
        MvrModel::query()->updateOrcreate(['name' => 'RAV4','mvr_make_id'=>1]);
        MvrModel::query()->updateOrcreate(['name' => 'Probox','mvr_make_id'=>1]);

        MvrModel::query()->updateOrcreate(['name' => 'Forester','mvr_make_id'=>2]);
        MvrModel::query()->updateOrcreate(['name' => 'Legacy','mvr_make_id'=>2]);
        MvrModel::query()->updateOrcreate(['name' => 'Forester XT','mvr_make_id'=>2]);

        MvrModel::query()->updateOrcreate(['name' => 'XTrail','mvr_make_id'=>3]);
        MvrModel::query()->updateOrcreate(['name' => 'Tiida','mvr_make_id'=>3]);
        MvrModel::query()->updateOrcreate(['name' => 'Dualis','mvr_make_id'=>3]);
    }
}
