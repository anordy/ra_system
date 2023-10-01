<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Builder;

trait RegistrationReportTrait
{
    public function getBusinessBuilder($parameters): Builder
    {
        $businessLocations = BusinessLocation::distinct('business_locations.id')
            ->join('businesses', 'businesses.id', 'business_locations.business_id');
        //get criteria                                
        switch ($parameters['criteria']) {
            case 'All-Business':
                $businessLocations = $businessLocations;
                break;
            case 'Business-Reg-By-Nature':
                $columnName = $this->getIsiicColumnName($parameters['isic_level']);
                $businessLocations->whereIn($columnName, $parameters['isic_id']);
                break;
            case 'Business-Reg-By-TaxType':
                if($parameters['taxtype_id'] == 'all') {
                    $businessLocations = $businessLocations->join('business_tax_type', 'business_tax_type.business_id', 'businesses.id');
                } else {
                    $businessLocations->join('business_tax_type', 'business_tax_type.business_id', 'businesses.id')->where('business_tax_type.tax_type_id', $parameters['taxtype_id']);
                }
                break;
            case 'Business-Reg-By-TaxPayer':
                    $businessLocations = $businessLocations;
                break;
        }
     
        //get period
        if ($parameters['year'] != "all" && $parameters['year'] != "range") {
            $businessLocations->whereYear('business_locations.approved_on', '=', $parameters['year']);
            if ($parameters['month'] != 'all') {
                $businessLocations->whereMonth('business_locations.approved_on', '=', $parameters['month']);
            }
        }
        if ($parameters['year'] == "range") {
            $businessLocations->whereBetween('business_locations.approved_on', [$parameters['range_start'], $parameters['range_end']]);
        }
        //get tax regions
        $businessLocations->whereIn('business_locations.tax_region_id', $parameters['tax_regions']);
        //get business category
        $businessLocations->whereIn('businesses.business_category_id', $parameters['category_ids']);
        //get business activities
        $businessLocations->whereIn('businesses.business_activities_type_id', $parameters['activity_ids']);
        //get business consultant type
        if (count($parameters['consultants']) < 2) {
            if (array_key_exists("own", $parameters['consultants'])) {
                $businessLocations->where('businesses.is_own_consultant', true);
            }
            if (array_key_exists("other", $parameters['consultants'])) {
                $businessLocations->where('businesses.is_own_consultant', false);
            }
        }
        //get physical location
        if ($parameters['region'] !== "all") {
            $businessLocations->where('business_locations.region_id', $parameters['region']);
            if ($parameters['district'] !== "all") {
                $businessLocations->where('business_locations.district_id', $parameters['district']);
                if ($parameters['ward'] !== "all") {
                    $businessLocations->where('business_locations.ward_id', $parameters['ward']);
                }
            }
        }
        return $businessLocations;
    }

    public function getIsiicColumnName($level)
    {
        if ($level == 1) {
            return 'isiic_i';
        } elseif ($level == 2) {
            return 'isiic_ii';
        } elseif ($level == 3) {
            return 'isiic_iii';
        } elseif ($level == 4) {
            return 'isiic_iv';
        }
    }
}
