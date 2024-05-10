<?php

namespace App\Traits;

use App\Enum\BusinessReportType;
use App\Enum\ReportStatus;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Builder;

trait RegistrationReportTrait
{
    /**
     * @throws \Exception
     */
    public function getBusinessBuilder($parameters): Builder
    {
        // Check if all required keys are present in the $parameters array
        if (!isset($parameters['criteria']) ||
            !isset($parameters['year']) ||
            !isset($parameters['month']) ||
            !isset($parameters['range_start']) ||
            !isset($parameters['range_end']) ||
            !isset($parameters['tax_regions']) ||
            !isset($parameters['category_ids']) ||
            !isset($parameters['activity_ids']) ||
            !isset($parameters['consultants']) ||
            !isset($parameters['region']) ||
            !isset($parameters['district']) ||
            !isset($parameters['ward'])) {
            throw new \InvalidArgumentException("Missing required parameters");
        }

        $businessLocations = BusinessLocation::distinct('business_locations.id')
            ->join('businesses', 'businesses.id', 'business_locations.business_id');

        switch ($parameters['criteria']) {
            case BusinessReportType::NATURE:
                $columnName = $this->getIsiicColumnName($parameters['isic_level']);
                $businessLocations->whereIn($columnName, $parameters['isic_id']);
                break;
            case BusinessReportType::TAX_TYPE:
                if($parameters['taxtype_id'] == ReportStatus::all) {
                    $businessLocations = $businessLocations->join('business_tax_type', 'business_tax_type.business_id', 'businesses.id');
                } else {
                    $businessLocations->join('business_tax_type', 'business_tax_type.business_id', 'businesses.id')->where('business_tax_type.tax_type_id', $parameters['taxtype_id']);
                }
                break;
            case BusinessReportType::WO_ZNO:
                    $businessLocations = $businessLocations->where('businesses.previous_zno', null)
                        ->where('business_locations.is_headquarter', 1);
                break;

            default:
                throw new \Exception("Invalid Business Report Type");
        }

        if ($parameters['year'] != ReportStatus::all && $parameters['year'] != ReportStatus::range) {
            $businessLocations->whereYear('business_locations.approved_on', '=', $parameters['year']);
            if ($parameters['month'] != ReportStatus::all) {
                $businessLocations->whereMonth('business_locations.approved_on', '=', $parameters['month']);
            }
        }

        if ($parameters['year'] == ReportStatus::range) {
            $businessLocations->whereBetween('business_locations.approved_on', [$parameters['range_start'], $parameters['range_end']]);
        }

        $businessLocations->whereIn('business_locations.tax_region_id', $parameters['tax_regions']);
        $businessLocations->whereIn('businesses.business_category_id', $parameters['category_ids']);
        $businessLocations->whereIn('businesses.business_activities_type_id', $parameters['activity_ids']);

        if (count($parameters['consultants']) < 2) {
            if (array_key_exists(ReportStatus::own, $parameters['consultants'])) {
                $businessLocations->where('businesses.is_own_consultant', true);
            }
            if (array_key_exists(ReportStatus::other, $parameters['consultants'])) {
                $businessLocations->where('businesses.is_own_consultant', false);
            }
        }

        if ($parameters['region'] !== ReportStatus::all) {
            $businessLocations->where('business_locations.region_id', $parameters['region']);
            if ($parameters['district'] !== ReportStatus::all) {
                $businessLocations->where('business_locations.district_id', $parameters['district']);
                if ($parameters['ward'] !== ReportStatus::all) {
                    $businessLocations->where('business_locations.ward_id', $parameters['ward']);
                }
            }
        }
        return $businessLocations;
    }

    public function getIsiicColumnName($level)
    {
        if ($level == 1) {
            return ReportStatus::ISIIC_1;
        } elseif ($level == 2) {
            return ReportStatus::ISIIC_2;
        } elseif ($level == 3) {
            return ReportStatus::ISIIC_3;
        } elseif ($level == 4) {
            return ReportStatus::ISIIC_4;
        }
    }
}
