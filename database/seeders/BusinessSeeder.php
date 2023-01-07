<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessBank;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\TaxType;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            "tin" => "543980345",
            "reg_no" => "12345",
            "owner_designation" => "Director General",
            "mobile" => "0743300900",
            "alt_mobile" => null,
            "email" => "goodman@mailinator.com",
            "place_of_business" => "Mazizini",
            "goods_and_services_types" => "Food and drinks",
            "goods_and_services_example" => "Azam-cola and Minute maid",
            "is_own_consultant" => 1,
            "responsible_person_id" => 1,
            "status" => "approved",
            "isiic_i" => 13,
            "isiic_ii" => 62,
            "isiic_iii" => 45,
            "isiic_iv" => 32,
            "verified_at" => "2022-01-01",
            "previous_zno" => 'Z8898933'
        ];

        $location = [
            "region_id" => 1,
            "district_id" => 1,
            "ward_id" => 1,
            "street_id" => 108,
            "latitude" => "4.4343",
            "longitude" => "88.4334",
            "nature_of_possession" => "Owned",
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
            "pre_estimated_turnover" => 1200000,
            "post_estimated_turnover" => 340000,
            "approved_on"=> Carbon::now()
        ];

        $location_two = [
            "region_id" => 1,
            "district_id" => 1,
            "ward_id" => 1,
            "latitude" => "2.5454",
            "longitude" => "34.4343",
            "nature_of_possession" => "Owned",
            "street_id" => 108,
            "physical_address" => "PO BOX 345 Stone Town",
            "house_no" => "9000",
            "owner_name" => null,
            "owner_phone_no" => null,
            "meter_no" => "900900",
            "taxpayer_id" => 1,
            "name" => "Stone Town Branch",
            "is_headquarter" => 0,
            "status" => "approved",
            "tax_region_id" => 2,
            "zin" => 123,
            "date_of_commencing" => "2022-07-01",
            "pre_estimated_turnover" => 45000000,
            "post_estimated_turnover" => 20000000,
            "approved_on"=> Carbon::now()
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
            $business->locations()->create($location_two);
            $business->banks()->create($bank);

            $taxTypes = TaxType::where('category', 'main')->pluck('id')->toArray();

            foreach ($taxTypes as $tax) {
                BusinessTaxType::create(["business_id" => $business->id,"tax_type_id" => $tax, "currency" => "TZS"]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
