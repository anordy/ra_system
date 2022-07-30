<?php

namespace Database\Seeders;

use App\Models\VatReturn\VatCategory;
use App\Models\VatReturn\VatService;
use Illuminate\Database\Seeder;

class VatCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplies = array("Standard rate supplies"=>"SRS", "Zero rated supplies"=>"ZRS", "Exempt supplies"=>"ES", "Special relief"=>"SR");

        $serivices= VatService::query()->where('code', 'SUP')->first();
        foreach ($supplies as $x=> $name) {
            VatCategory::query()->updateOrCreate([
                'name' => $x,
                'code'=> $name,
                'vat_service_code' => $serivices->code,
            ]);
        }

        $purchases = array("Exempt Import Purchases"=>"EIP", "Exempt local purchases"=>"ELP", "Non credible purchases"=>"NCP",
            "VAT differed purchases"=>"VDP", "Standard local purchases"=>"SLP", "Standard rated imports"=>"SRI", "Infrastructure tax (Electricity)"=>"ITE",
         "Infrastructure tax (Hotel)"=>"ITH");

        $serivices= VatService::query()->where('code', 'PUR')->first();
        foreach ($purchases as $x=> $name) {
            VatCategory::query()->updateOrCreate([
                'name' => $x,
                'code'=> $name,
                'vat_service_code' => $serivices->code,
            ]);
        }
    }
}
