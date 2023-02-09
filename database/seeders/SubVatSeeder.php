<?php

namespace Database\Seeders;

use App\Enum\SubVatConstant;
use App\Models\Returns\Vat\SubVat;
use Illuminate\Database\Seeder;

class SubVatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111010003', 'name' => SubVatConstant::CIGARATTES, 'code' => SubVatConstant::CIGARATTES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111220003', 'name' => SubVatConstant::OTHERCHEMICALS, 'code' => SubVatConstant::OTHERCHEMICALS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111230003', 'name' => SubVatConstant::PLASTICS, 'code' => SubVatConstant::PLASTICS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111330003', 'name' => SubVatConstant::ALUMINIUM, 'code' => SubVatConstant::ALUMINIUM]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111550001', 'name' => SubVatConstant::BUILDINGCONTRACTORS, 'code' => SubVatConstant::BUILDINGCONTRACTORS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111490001', 'name' => SubVatConstant::RETAILERS, 'code' => SubVatConstant::RETAILERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111600001', 'name' => SubVatConstant::AUCTIONEERS, 'code' => SubVatConstant::AUCTIONEERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111610001', 'name' => SubVatConstant::CONSULTANCY, 'code' => SubVatConstant::CONSULTANCY]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111850001', 'name' => SubVatConstant::TELEXANDFAXS, 'code' => SubVatConstant::TELEXANDFAXS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111580001', 'name' => SubVatConstant::ACCOUNTANTS, 'code' => SubVatConstant::ACCOUNTANTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111830001', 'name' => SubVatConstant::DRIVINGSCHOOLS, 'code' => SubVatConstant::DRIVINGSCHOOLS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111820001', 'name' => SubVatConstant::VALUATIONSERVICES, 'code' => SubVatConstant::VALUATIONSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111960001', 'name' => SubVatConstant::REFUND, 'code' => SubVatConstant::REFUND]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111920001', 'name' => SubVatConstant::NATURALGAS, 'code' => SubVatConstant::NATURALGAS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111770001', 'name' => SubVatConstant::APPLIANCESREPAIR, 'code' => SubVatConstant::APPLIANCESREPAIR]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111200003', 'name' => SubVatConstant::METALPRODUCTS, 'code' => SubVatConstant::METALPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111160003', 'name' => SubVatConstant::TEAANDCOFFEE, 'code' => SubVatConstant::TEAANDCOFFEE]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111290003', 'name' => SubVatConstant::BREADANDBISCUITS, 'code' => SubVatConstant::BREADANDBISCUITS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111120003', 'name' => SubVatConstant::KIBUKU, 'code' => SubVatConstant::KIBUKU]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111530001', 'name' => SubVatConstant::CATERINGSERVICES, 'code' => SubVatConstant::CATERINGSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111560001', 'name' => SubVatConstant::ELECTRICALCONTRACTORS, 'code' => SubVatConstant::ELECTRICALCONTRACTORS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111470001', 'name' => SubVatConstant::ELECTRICITY, 'code' => SubVatConstant::ELECTRICITY]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111630001', 'name' => SubVatConstant::VEHICLEREPAIRS, 'code' => SubVatConstant::VEHICLEREPAIRS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111640001', 'name' => SubVatConstant::TOUROPERATORS, 'code' => SubVatConstant::TOUROPERATORS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111420003', 'name' => SubVatConstant::BOTTLEDWATER, 'code' => SubVatConstant::BOTTLEDWATER]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111020003', 'name' => SubVatConstant::PETROLEUM, 'code' => SubVatConstant::PETROLEUM]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111030003', 'name' => SubVatConstant::SUGAR, 'code' => SubVatConstant::SUGAR]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111070003', 'name' => SubVatConstant::TEXTILES, 'code' => SubVatConstant::TEXTILES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111250003', 'name' => SubVatConstant::LOCALLYASSEMBLEDMVS, 'code' => SubVatConstant::LOCALLYASSEMBLEDMVS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111340003', 'name' => SubVatConstant::COTTONANDKAPOK, 'code' => SubVatConstant::COTTONANDKAPOK]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111210003', 'name' => SubVatConstant::LEATHERPRODUCTS, 'code' => SubVatConstant::LEATHERPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111320003', 'name' => SubVatConstant::CEREMICPRODUCTS, 'code' => SubVatConstant::CEREMICPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111430003', 'name' => SubVatConstant::MEDICINES, 'code' => SubVatConstant::MEDICINES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111570001', 'name' => SubVatConstant::ENGINEERINGSERVICES, 'code' => SubVatConstant::ENGINEERINGSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111740001', 'name' => SubVatConstant::SECURITYSERVICES, 'code' => SubVatConstant::SECURITYSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111810001', 'name' => SubVatConstant::QUANTITYSURVEYORS, 'code' => SubVatConstant::QUANTITYSURVEYORS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111890001', 'name' => SubVatConstant::TELECOMMUNICATIONDATASERVICES, 'code' => SubVatConstant::TELECOMMUNICATIONDATASERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111870001', 'name' => SubVatConstant::AIRCARGOSERVICES, 'code' => SubVatConstant::AIRCARGOSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111090003', 'name' => SubVatConstant::ELECTRICALPRODUCTS, 'code' => SubVatConstant::ELECTRICALPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111060003', 'name' => SubVatConstant::BEER, 'code' => SubVatConstant::BEER]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111190003', 'name' => SubVatConstant::PERFUMESANDCOSMETICS, 'code' => SubVatConstant::PERFUMESANDCOSMETICS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111350003', 'name' => SubVatConstant::MVSPARESANDBICYCLES, 'code' => SubVatConstant::MVSPARESANDBICYCLES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111270003', 'name' => SubVatConstant::MILKANDMILKPRODUCTS, 'code' => SubVatConstant::MILKANDMILKPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111110003', 'name' => SubVatConstant::SPIRITS, 'code' => SubVatConstant::SPIRITS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111280003', 'name' => SubVatConstant::SWEETSANDCONFECTIONERIES, 'code' => SubVatConstant::SWEETSANDCONFECTIONERIES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111360003', 'name' => SubVatConstant::AGRICULTURALPRODUCTS, 'code' => SubVatConstant::AGRICULTURALPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111450003', 'name' => SubVatConstant::INSURANCE, 'code' => SubVatConstant::INSURANCE]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111620001', 'name' => SubVatConstant::CLEARINGANDFORWARDING, 'code' => SubVatConstant::CLEARINGANDFORWARDING]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111130003', 'name' => SubVatConstant::WINESANDLIQUOR, 'code' => SubVatConstant::WINESANDLIQUOR]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111100003', 'name' => SubVatConstant::KONYAGI, 'code' => SubVatConstant::KONYAGI]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111150003', 'name' => SubVatConstant::WHEATANDFLOUR, 'code' => SubVatConstant::WHEATANDFLOUR]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111310003', 'name' => SubVatConstant::MATCHES, 'code' => SubVatConstant::MATCHES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111380003', 'name' => SubVatConstant::FISHPRODUCTS, 'code' => SubVatConstant::FISHPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111410003', 'name' => SubVatConstant::NAILS, 'code' => SubVatConstant::NAILS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111370003', 'name' => SubVatConstant::FORESTRYPRODUCTS, 'code' => SubVatConstant::FORESTRYPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111500001', 'name' => SubVatConstant::WHOLESALERS, 'code' => SubVatConstant::WHOLESALERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111690001', 'name' => SubVatConstant::BARBERSHOPS, 'code' => SubVatConstant::BARBERSHOPS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111540001', 'name' => SubVatConstant::OTHERSERVICES, 'code' => SubVatConstant::OTHERSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111800001', 'name' => SubVatConstant::AIRCHARTERS, 'code' => SubVatConstant::AIRCHARTERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111480001', 'name' => SubVatConstant::TELEPHONE, 'code' => SubVatConstant::TELEPHONE]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111790001', 'name' => SubVatConstant::CARRENTALS, 'code' => SubVatConstant::CARRENTALS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111750001', 'name' => SubVatConstant::COURIERSERVICES, 'code' => SubVatConstant::COURIERSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111940001', 'name' => SubVatConstant::IMPORTS, 'code' => SubVatConstant::IMPORTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111930001', 'name' => SubVatConstant::FINANCIALSERVICES, 'code' => SubVatConstant::FINANCIALSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111840001', 'name' => SubVatConstant::JEWELLERS, 'code' => SubVatConstant::JEWELLERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111050003', 'name' => SubVatConstant::CEMENT, 'code' => SubVatConstant::CEMENT]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111140003', 'name' => SubVatConstant::COOKINGOIL, 'code' => SubVatConstant::COOKINGOIL]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111180003', 'name' => SubVatConstant::TYRESANDTUBES, 'code' => SubVatConstant::TYRESANDTUBES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111240003', 'name' => SubVatConstant::PAINTS, 'code' => SubVatConstant::PAINTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111260003', 'name' => SubVatConstant::FURNITUREANDWOODPRODUCTS, 'code' => SubVatConstant::FURNITUREANDWOODPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111510001', 'name' => SubVatConstant::TRANSPORT, 'code' => SubVatConstant::TRANSPORT]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111700001', 'name' => SubVatConstant::TAILORINGMARTS, 'code' => SubVatConstant::TAILORINGMARTS]); 
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111710001', 'name' => SubVatConstant::SECRETARIALSERVICES, 'code' => SubVatConstant::SECRETARIALSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111720001', 'name' => SubVatConstant::RADIOSANDTELEVISION, 'code' => SubVatConstant::RADIOSANDTELEVISION]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111760001', 'name' => SubVatConstant::FUMIGATIONSERVICES, 'code' => SubVatConstant::FUMIGATIONSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111780001', 'name' => SubVatConstant::BOATCHARTERERS, 'code' => SubVatConstant::BOATCHARTERERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111730001', 'name' => SubVatConstant::ARCHITECTURAL, 'code' => SubVatConstant::ARCHITECTURAL]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111880001', 'name' => SubVatConstant::HOTELLEVY, 'code' => SubVatConstant::HOTELLEVY]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111040003', 'name' => SubVatConstant::SOFTDRINKS, 'code' => SubVatConstant::SOFTDRINKS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111080003', 'name' => SubVatConstant::SOAPSANDDETERGENTS, 'code' => SubVatConstant::SOAPSANDDETERGENTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111170003', 'name' => SubVatConstant::PAPERANDPAPERPRODUCTS, 'code' => SubVatConstant::PAPERANDPAPERPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111300003', 'name' => SubVatConstant::FRUITANDJUICES, 'code' => SubVatConstant::FRUITANDJUICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111460003', 'name' => SubVatConstant::ROYALTY, 'code' => SubVatConstant::ROYALTY]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111520001', 'name' => SubVatConstant::HOTELSERVICES, 'code' => SubVatConstant::HOTELSERVICES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111650001', 'name' => SubVatConstant::LAUNDRYANDDRYCLEANERS, 'code' => SubVatConstant::LAUNDRYANDDRYCLEANERS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111660001', 'name' => SubVatConstant::PHOTOSTUDIOS, 'code' => SubVatConstant::PHOTOSTUDIOS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111860001', 'name' => SubVatConstant::RENTONLEASEDBUILDING, 'code' => SubVatConstant::RENTONLEASEDBUILDING]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111400003', 'name' => SubVatConstant::SALT, 'code' => SubVatConstant::SALT]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111390003', 'name' => SubVatConstant::ROOFINGMATERIALS, 'code' => SubVatConstant::ROOFINGMATERIALS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111590001', 'name' => SubVatConstant::ADVOCATES, 'code' => SubVatConstant::ADVOCATES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111440003', 'name' => SubVatConstant::INTEREST, 'code' => SubVatConstant::INTEREST]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111670001', 'name' => SubVatConstant::FITNESSCENTRES, 'code' => SubVatConstant::FITNESSCENTRES]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111680001', 'name' => SubVatConstant::HAIRSALOON, 'code' => SubVatConstant::HAIRSALOON]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111910001', 'name' => SubVatConstant::COMPUTERPRODUCTS, 'code' => SubVatConstant::COMPUTERPRODUCTS]);
        SubVat::updateOrCreate(['is_approved' => 1, 'gfs_code' => '114111900001', 'name' => SubVatConstant::TELECOMMUNICATIONVOICESERVICES, 'code' => SubVatConstant::TELECOMMUNICATIONVOICESERVICES]);

    }
}
