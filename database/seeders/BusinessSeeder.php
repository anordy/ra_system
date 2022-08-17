<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessBank;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business = [
            "business_activities_type_id" => 2,
            "business_category_id" => 1,
            "currency_id" => 1,
            "taxpayer_id" => 1,
            "name" => "Goodman Enterprises",
            "tin" => "543980",
            "reg_no" => "12345",
            "owner_designation" => "Director General",
            "mobile" => "0743300900",
            "alt_mobile" => null,
            "email" => "goodman@mailinator.com",
            "place_of_business" => "Mazizini",
            "physical_address" => "PO BOX 456 Mazizini",
            "pre_estimated_turnover" => "12000000",
            "post_estimated_turnover" => "0",
            "goods_and_services_types" => "Food and drinks",
            "goods_and_services_example" => "Azam-cola and Minute maid",
            "is_own_consultant" => 1,
            "responsible_person_id" => 1,
            "status" => "approved",
            "isiic_i" => 13,
            "isiic_ii" => 62,
            "isiic_iii" => 45,
            "isiic_iv" => 32,
            "verified_at" => "2022-01-01"
        ];

        $location = [
            "region_id" => 1,
            "district_id" => 1,
            "ward_id" => 2,
            "latitude" => "4.4343",
            "longitude" => "88.4334",
            "nature_of_possession" => "Owned",
            "street" => "Mazizini",
            "physical_address" => "PO BOX 456 Mazizini",
            "house_no" => "4545",
            "owner_name" => null,
            "owner_phone_no" => null,
            "meter_no" => "123443",
            "taxpayer_id" => 1,
            "name" => "Mazizini Branch",
            "is_headquarter" => 1,
            "status" => "approved",
            "tax_region_id" => 1,
            "zin" => 1,
            "date_of_commencing" => "2022-01-01",
        ];

        $bank = [
            "bank_id" => 5,
            "acc_no" => "0124548904",
            "account_type_id" => 4,
            "branch" => "Stone Town Branch",
            "currency_id" => 1
        ];

        DB::beginTransaction();

        try {
            $business = Business::create($business);
            $business->headquarter()->create($location);
            $business->banks()->create($bank);

            for ($i=1; $i < 16; $i++) { 
                BusinessTaxType::create(["business_id" => $business->id,"tax_type_id" => $i, "currency" => "TZS"]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
