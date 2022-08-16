<?php

namespace App\Http\Livewire\TaxClearance;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use Livewire\Component;

class TaxClearanceRequest extends Component
{
    public $petroleumReturn;
    public $petroleumTotal;
    public $hotelReturn;
    public $stampDuty;
    public $taxAssesment;
    public $portReturn;
    public $tourLevy;
    public $restaurantLevy;
    public $tourLevyId;
    public $totalInfrastructure = 0;

    public function mount($business_location_id){
        
        $this->petroleumReturn = PetroleumReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->totalInfrastructure += $this->petroleumReturn->sum('infrastructure_tax');
        $this->petroleumTotal += $this->petroleumReturn->sum('infrastructure_tax');
        
        $this->hotelReturn = HotelReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();
        dd($this->hotelReturn);

        $this->stampDuty = StampDutyReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->portReturn = PortReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->taxAssesment = TaxAssessment::where('location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->tourLevyId = TaxType::where('code', TaxType::TOUR_OPERATOR)->pluck('id');
        // dd($this->tourLevyId);
        $this->tourLevy = HotelReturn::where('business_location_id', $business_location_id)->where('tax_type_id', $this->tourLevyId)
        ->where('status', '!=' ,'complete')
        ->sum('total_amount_due_with_penalties');

        $restaurantLevyId = TaxType::where('code', TaxType::RESTAURANT)->pluck('id');
        $this->restaurantLevy = HotelReturn::where('business_location_id', $business_location_id)->where('tax_type_id', $restaurantLevyId)
        ->where('status', '!=' ,'complete')
        ->sum('total_amount_due_with_penalties');

        dd($this->tourLevy);

    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
