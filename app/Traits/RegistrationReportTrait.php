<?php

namespace App\Traits;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Builder;

trait RegistrationReportTrait
{
    //business by nature query
    public function businessByNatureQuery($isic1Id) : Builder
    {
        return BusinessLocation::query()
                ->join('businesses', 'businesses.id', 'business_locations.business_id')
                ->select('*')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->where('business_locations.status', BusinessStatus::APPROVED)
                ->where('businesses.isiic_i', $isic1Id);
    }

    //check if there is any business by nature
    public function hasBusinessByNature($isic1Id) :bool
    {
        $isic_id = $isic1Id;
        $all = $this->businessByNatureQuery($isic_id)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

    public function businessByTaxTypeQuery($tax_type_id) : Builder
    {
        return BusinessLocation::query()
                ->join('businesses', 'businesses.id', 'business_locations.business_id')
                ->join('business_tax_type','business_tax_type.business_id','businesses.id')
                ->select('*')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->where('business_locations.status', BusinessStatus::APPROVED)
                ->where('business_tax_type.tax_type_id', $tax_type_id);
    }

    public function hasBusinessByTaxType($tax_type_id): bool
    {
        $t = $tax_type_id;
        $all = $this->businessByTaxTypeQuery($t)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

    public function businessByTurnOverLastQuery($from,$to) : Builder
    {
        return Business::query()
                ->select('*')
                ->where('status', BusinessStatus::APPROVED)
                ->where('pre_estimated_turnover','>=',$from)
                ->where('pre_estimated_turnover','<=',$to);
    }

    public function hasBusinessByTurnOverLast($from,$to): bool
    {
        $from = $from;
        $to = $to;
        $all = $this->businessByTurnOverLastQuery($from,$to)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

    public function businessByTurnOverNextQuery($from,$to) : Builder
    {
        return Business::query()
                ->select('*')
                ->where('status', BusinessStatus::APPROVED)
                ->where('post_estimated_turnover','>=',$from)
                ->where('post_estimated_turnover','<=',$to);
    }

    public function hasBusinessByTurnOverNext($from,$to): bool
    {
        $from = $from;
        $to = $to;
        $all = $this->businessByTurnOverNextQuery($from,$to)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

}
