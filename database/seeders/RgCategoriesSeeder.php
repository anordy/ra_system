<?php

namespace Database\Seeders;

use App\Enum\ReportRegister\RgRequestorType;
use App\Models\ReportRegister\RgCategory;
use App\Models\ReportRegister\RgSubCategory;
use Illuminate\Database\Seeder;

class RgCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            ['name' => 'Tax Returns', 'requester_type' => RgRequestorType::TAXPAYER],
        ];

        foreach ($data as $row) {
            $category = RgCategory::updateOrCreate($row);
            RgSubCategory::updateOrCreate(['name' => 'VAT', 'rg_category_id' => $category->id, 'requester_type' => RgRequestorType::TAXPAYER]);
        }

    }
}
