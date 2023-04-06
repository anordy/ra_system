<?php

namespace App\Enum;

use ReflectionClass;

class SubVatConstant implements Status
{
    public const CIGARATTES = 'VAT on  Cigarattes';
    public const PETROLEUM = 'VAT on Petroleum';
    public const SUGAR = 'VAT on sugar';
    public const SOFTDRINKS = 'VAT on Soft Drinks';
    public const CEMENT = 'VAT on Cement';
    public const BEER = 'VAT on  Beer';
    public const TEXTILES = 'VAT on Textiles';
    public const SOAPSANDDETERGENTS = 'VAT on Soaps and Detergents';
    public const ELECTRICALPRODUCTS = 'VAT on Electrical Products';
    public const KONYAGI = 'VAT on Konyagi';
    public const SPIRITS = 'VAT on Spirits';
    public const KIBUKU = 'VAT on Kibuku';
    public const WINESANDLIQUOR = 'VAT on Wines and Liquor';
    public const COOKINGOIL = 'VAT on Cooking Oil';
    public const WHEATANDFLOUR = 'VAT on Wheat and Flour';
    public const TEAANDCOFFEE = 'VAT on Tea and Coffee';
    public const PAPERANDPAPERPRODUCTS = 'VAT on Paper and Paper Products';
    public const TYRESANDTUBES = 'VAT on Tyres and Tubes';
    public const PERFUMESANDCOSMETICS = 'VAT on Perfumes and Cosmetics';
    public const METALPRODUCTS = 'VAT on Metal Products';
    public const LEATHERPRODUCTS = 'VAT on Leather Products';
    public const OTHERCHEMICALS = 'VAT on Other Chemicals';
    public const PLASTICS = 'VAT on Plastics';
    public const PAINTS = 'VAT on Paints';
    public const LOCALLYASSEMBLEDMVS = 'VAT on Locally Assembled MVs';
    public const FURNITUREANDWOODPRODUCTS = 'VAT on Furniture and Wood Products';
    public const MILKANDMILKPRODUCTS = 'VAT on Milk and Milk Products';
    public const SWEETSANDCONFECTIONERIES = 'VAT on Sweets and Confectioneries';
    public const BREADANDBISCUITS = 'VAT on Bread and Biscuits';
    public const FRUITANDJUICES = 'VAT on Fruit and Juices';
    public const MATCHES = 'VAT on Matches';
    public const CEREMICPRODUCTS = 'VAT on Ceremic Products';
    public const ALUMINIUM = 'VAT on Aluminium';
    public const COTTONANDKAPOK = 'VAT on Cotton and Kapok';
    public const MVSPARESANDBICYCLES = 'VAT on MV Spares and Bicycles';
    public const AGRICULTURALPRODUCTS = 'VAT on Agricultural Products';
    public const FORESTRYPRODUCTS = 'VAT on Forestry Products';
    public const FISHPRODUCTS = 'VAT on Fish Products';
    public const ROOFINGMATERIALS = 'VAT on Roofing Materials';
    public const NAILS = 'VAT on Nails';
    public const BOTTLEDWATER = 'VAT on Bottled Water';
    public const MEDICINES = 'VAT on Medicines';
    public const INTEREST = 'VAT on Interest';
    public const INSURANCE = 'VAT on Insurance';
    public const ROYALTY = 'VAT on Royalty';
    public const ELECTRICITY = 'VAT on  Electricity';
    public const TELEPHONE = 'VAT on Telephone';
    public const RETAILERS = 'VAT on Retailers';
    public const WHOLESALERS = 'VAT on Wholesalers';
    public const TRANSPORT = 'VAT on Transport';
    public const HOTELSERVICES = 'VAT on  Hotel Services';
    public const CATERINGSERVICES = 'VAT on Catering Services';
    public const OTHERSERVICES = 'VAT on Other Services';
    public const BUILDINGCONTRACTORS = 'VAT on Building Contractors';
    public const ELECTRICALCONTRACTORS = 'VAT on Electrical Contractors';
    public const ENGINEERINGSERVICES = 'VAT on Engineering Services';
    public const ACCOUNTANTS = 'VAT on Accountants';
    public const ADVOCATES = 'VAT on Advocates';
    public const AUCTIONEERS = 'VAT on Auctioneers';
    public const CONSULTANCY = 'VAT on Consultancy';
    public const CLEARINGANDFORWARDING = 'VAT on Clearing and Forwarding';
    public const VEHICLEREPAIRS = 'VAT on Vehicle Repairs';
    public const TOUROPERATORS = 'VAT on Tour Operators';
    public const LAUNDRYANDDRYCLEANERS = 'VAT on Laundry and Dry Cleaners';
    public const PHOTOSTUDIOS = 'VAT on Photo Studios';
    public const FITNESSCENTRES = 'VAT on Fitness Centres';
    public const HAIRSALOON = 'VAT on Hair Saloon';
    public const BARBERSHOPS = 'VAT on Barber Shops';
    public const TAILORINGMARTS = 'VAT on Tailoring Marts';
    public const SECRETARIALSERVICES = 'VAT on Secretarial Services';
    public const RADIOSANDTELEVISION = 'VAT on Radios and Television';
    public const ARCHITECTURAL = 'VAT on Architectural';
    public const SECURITYSERVICES = 'VAT on Security Services';
    public const COURIERSERVICES = 'VAT on Courier Services';
    public const FUMIGATIONSERVICES = 'VAT on Fumigation Services';
    public const APPLIANCESREPAIR = 'VAT on Appliances Repair';
    public const BOATCHARTERERS = 'VAT on Boat Charterers';
    public const CARRENTALS = 'VAT on Car Rentals';
    public const AIRCHARTERS = 'VAT on Air Charters';
    public const QUANTITYSURVEYORS = 'VAT on Quantity Surveyors';
    public const VALUATIONSERVICES = 'VAT on Valuation Services';
    public const DRIVINGSCHOOLS = 'VAT on Driving Schools';
    public const JEWELLERS = 'VAT on Jewellers';
    public const TELEXANDFAXS = 'VAT on Telex and Faxs';
    public const RENTONLEASEDBUILDING = 'VAT from rent on leased building';
    public const AIRCARGOSERVICES = 'VAT on air cargo services';
    public const HOTELLEVY = 'VAT on Hotel levy';
    public const TELECOMMUNICATIONDATASERVICES = 'VAT on  Telecommunication data services';
    public const TELECOMMUNICATIONVOICESERVICES = 'VAT on  Telecommunication Voice services';
    public const COMPUTERPRODUCTS = 'VAT on Computer products';
    public const NATURALGAS = 'VAT on Natural gas';
    public const FINANCIALSERVICES = 'VAT on Financial Services';
    public const IMPORTS = 'VAT on Imports';
    public const REFUND = 'VAT Refund';
    public const SALT = 'VAT on Salt';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}