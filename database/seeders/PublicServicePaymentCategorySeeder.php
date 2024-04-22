<?php

namespace Database\Seeders;

use App\Models\PublicService\PublicServicePaymentCategory;
use Illuminate\Database\Seeder;

class PublicServicePaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Passengers /school and staff car', 'turnover_tax' => 300000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Three wheels vehicle', 'turnover_tax' => 150000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Private hire (1-6 passengers)', 'turnover_tax' => 375000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Private hire (above 6 passengers)', 'turnover_tax' => 525000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Vehicle (8-14 passengers)', 'turnover_tax' => 187500, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Vehicle (15-24 passengers)', 'turnover_tax' => 375000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Vehicle (25-36 passengers)', 'turnover_tax' => 450000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Vehicle (37 passengers and above)', 'turnover_tax' => 525000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Taxi Cabs', 'turnover_tax' => 300000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Driving School', 'turnover_tax' => 300000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (more than 10 tonnes)', 'turnover_tax' => 750000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (8 tonnes not exceeding 9)', 'turnover_tax' => 525000, 'currency' => 'TZS']);
            PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (4 tonnes to 7 tonnes)', 'turnover_tax' => 375000, 'currency' => 'TZS']);
            // Not yet given on new tax law.
            // PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (1 tonne to 3 tonnes)', 'turnover_tax' => 300000, 'currency' => 'TZS']);
            // PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (10 tonnes or less with the combination with truck and trailer of more than 15 but less than 20 tonnes)', 'turnover_tax'=> 1200000, 'currency' => 'TZS']);
            // PublicServicePaymentCategory::updateOrcreate(['name' => 'Goods Vehicle (less than 1 tonne)', 'turnover_tax' => 100000, 'currency' => 'TZS']);
    }
}
