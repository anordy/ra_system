<?php

namespace App\Traits;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Builder;

trait RegistrationReportTrait
{
    //business by nature query for isic level 1
    public function businessByNatureIsic1Query($isic1Id) : Builder
    {
        return BusinessLocation::query()
                ->join('businesses', 'businesses.id', 'business_locations.business_id')
                ->select('*')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->where('business_locations.status', BusinessStatus::APPROVED)
                ->where('businesses.isiic_i', $isic1Id);
    }

    //check if there is any business by nature of isic 1
    public function hasBusinessByNatureIsic1($isic1Id) :bool
    {
        $isic1_id = $isic1Id;
        $all = $this->businessByNatureIsic1Query($isic1_id)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

    //business by nature query for isic level 2
    public function businessByNatureIsic2Query($isic2Id) : Builder
    {
        return BusinessLocation::query()
                ->join('businesses', 'businesses.id', 'business_locations.business_id')
                ->select('*')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->where('business_locations.status', BusinessStatus::APPROVED)
                ->where('businesses.isiic_ii', $isic2Id);
    }

    //check if there is any business by nature of isic 2
    public function hasBusinessByNatureIsic2($isic2Id) :bool
    {
        $isic2_id = $isic2Id;
        $all = $this->businessByNatureIsic2Query($isic2_id)->count();
        if($all > 0){
            return true;
        }
        return false;
    }

        //business by nature query for isic level 3
        public function businessByNatureIsic3Query($isic3Id) : Builder
        {
            return BusinessLocation::query()
                    ->join('businesses', 'businesses.id', 'business_locations.business_id')
                    ->select('*')
                    ->where('businesses.status', BusinessStatus::APPROVED)
                    ->where('business_locations.status', BusinessStatus::APPROVED)
                    ->where('businesses.isiic_iii', $isic3Id);
        }
    
        //check if there is any business by nature of isic 3
        public function hasBusinessByNatureIsic3($isic3Id) :bool
        {
            $isic3_id = $isic3Id;
            $all = $this->businessByNatureIsic3Query($isic3_id)->count();
            if($all > 0){
                return true;
            }
            return false;
        }

            //business by nature query for isic level 4
    public function businessByNatureIsic4Query($isic4Id) : Builder
    {
        return BusinessLocation::query()
                ->join('businesses', 'businesses.id', 'business_locations.business_id')
                ->select('*')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->where('business_locations.status', BusinessStatus::APPROVED)
                ->where('businesses.isiic_iv', $isic4Id);
    }

    //check if there is any business by nature of isic 4
    public function hasBusinessByNatureIsic4($isic4Id) :bool
    {
        $isic4_id = $isic4Id;
        $all = $this->businessByNatureIsic4Query($isic4_id)->count();
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

    public function businessByTurnOver($from,$to) : Builder
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
