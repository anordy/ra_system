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
    public $hotelTotal;
    public $stampDutyTotal;
    public $taxAssesment;
    public $portReturn;
    public $tourLevy;
    public $restaurantLevy;
    public $tourLevyId;
    public $restaurantLevyId;
    public $hotelLevyId;
    public $seaPortReturnId;
    public $seaPortReturn;
    public $seaPortReturnTotalTZS;
    public $airportReturnId;
    public $airportReturn;
    public $totalInfrastructure = 0;

    public function mount($business_location_id){
        
        $this->petroleumReturn = PetroleumReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        // dd($this->petroleumReturn);

        $this->totalInfrastructure += $this->petroleumReturn->sum('infrastructure_tax');
        $this->petroleumTotal += $this->petroleumReturn->sum('petroleum_levy');
        $this->petroleumTotal += $this->petroleumReturn->sum('penalty');
        $this->petroleumTotal += $this->petroleumReturn->sum('interest');

        // dd($this->petroleumTotal);
        
        $this->hotelLevyId = TaxType::where('code', TaxType::HOTEL)->pluck('id');
        $this->hotelReturn = HotelReturn::where('business_location_id', $business_location_id)
        ->whereIn('tax_type_id', $this->hotelLevyId)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->hotelTotal += ($this->hotelReturn->sum('total_amount_due') - $this->hotelReturn->sum('hotel_infrastructure_tax'));
        // dd($this->hotelTotal);
        $this->hotelTotal += $this->hotelReturn->sum('penalty');
        $this->hotelTotal += $this->hotelReturn->sum('interest');
        // dd($this->hotelTotal);

        $this->totalInfrastructure += $this->hotelReturn->sum('hotel_infrastructure_tax');
        // dd($this->totalInfrastructure);

        $this->stampDutyTotal = StampDutyReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->sum('total_amount_due_with_penalties');
        // dd($this->stampDutyTotal);

        $this->seaPortReturnId = TaxType::where('code', TaxType::SEA_SERVICE_TRANSPORT_CHARGE)->pluck('id');
        $this->seaPortReturn = PortReturn::where('business_location_id', $business_location_id)
        ->whereIn('tax_type_id', $this->seaPortReturnId)
        ->where('status', '!=' ,'complete')
        ->get();
        
        $this->seaPortReturnTotalTZS = $this->seaPortReturn->sum('infrastructure_znz_znz');
        $this->seaPortReturnTotalTZS += $this->seaPortReturn->sum('penalty');
        $this->seaPortReturnTotalTZS += $this->seaPortReturn->sum('interest');
        dd($this->seaPortReturnTotalTZS);


        $this->totalInfrastructure += $this->seaPortReturn->sum('infrastructure_znz_znz');
        $this->totalInfrastructure += $this->seaPortReturn->sum('infrastructure_znz_tm');


        $this->airportReturnId = TaxType::where('code', TaxType::AIRPORT_SERVICE_SAFETY_FEE)->pluck('id');
        $this->airportReturn = PortReturn::where('business_location_id', $business_location_id)
        ->whereIn('tax_type_id', $this->airportReturnId)
        ->where('status', '!=' ,'complete')
        ->get();

        $this->totalInfrastructure += $this->airportReturn->sum('infrastructure');


        $this->taxAssesment = TaxAssessment::where('location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();
        

        $this->tourLevyId = TaxType::where('code', TaxType::TOUR_OPERATOR)->pluck('id');
        $this->tourLevy = HotelReturn::where('business_location_id', $business_location_id)
        ->whereIn('tax_type_id', $this->tourLevyId)
        ->where('status', '!=' ,'complete')
        ->sum('total_amount_due_with_penalties');


        $this->restaurantLevyId = TaxType::where('code', TaxType::RESTAURANT)->pluck('id');
        $this->restaurantLevy = HotelReturn::where('business_location_id', $business_location_id)
        ->whereIn('tax_type_id', $this->restaurantLevyId)
        ->where('status', '!=' ,'complete')
        ->sum(' ');

        dd($this->tourLevy);

    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
