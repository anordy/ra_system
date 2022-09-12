<?php

namespace App\Observers;

use App\Enum\ApplicationStep;
use App\Enum\TaxClaimStatus;
use App\Traits\PenaltyTrait;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\HotelReturns\HotelReturn;

class HotelReturnObserver
{
    use PenaltyTrait;
    /**
     * Handle the HotelReturn "created" event.
     *
     * @param  \App\Models\HotelReturn  $hotelReturn
     * @return void
     */
    public function created(HotelReturn $hotelReturn)
    {
        $application_step = $this->hasReturnReachedDebt($hotelReturn->filing_due_date) == true ? ApplicationStep::DEBT: ApplicationStep::FILING;

        $has_claim = $hotelReturn->claim_status == TaxClaimStatus::CLAIM ? true : false;


        TaxReturn::create([
            'business_id' => $hotelReturn->business_id,
            'location_id' => $hotelReturn->business_location_id,
            'tax_type_id' => $hotelReturn->tax_type_id,

            'return_id' => $hotelReturn->id,
            'return_type' => HotelReturn::class,

            'currency' => $hotelReturn->currency,
            'filed_by_id' => $hotelReturn->filed_by_id,
            'filed_by_type' => $hotelReturn->filed_by_type,

            'principal' => $hotelReturn->total_amount_due,
            'interest' => $hotelReturn->interest,
            'penalty' => $hotelReturn->penalty,

            'infrastructure' => $hotelReturn->hotel_infrastructure_tax,
            'financial_month_id' => $hotelReturn->financial_month_id,
            
            'total_amount' => $hotelReturn->total_amount_due_with_penalties,
            'outstanding_amount' => $hotelReturn->total_amount_due_with_penalties,

            'filing_due_date' => $hotelReturn->filing_due_date,
            'payment_due_date' => $hotelReturn->payment_due_date,

            'curr_filing_due_date' => $hotelReturn->filing_due_date,
            'curr_payment_due_date' => $hotelReturn->payment_due_date,

            'return_category' => $hotelReturn->return_category,
            'application_step' => $application_step,

            'has_claim' => $has_claim
        ]);

    }

    /**
     * Handle the HotelReturn "updated" event.
     *
     * @param  \App\Models\HotelReturn  $hotelReturn
     * @return void
     */
    public function updated(HotelReturn $hotelReturn)
    {
        //
    }

    /**
     * Handle the HotelReturn "deleted" event.
     *
     * @param  \App\Models\HotelReturn  $hotelReturn
     * @return void
     */
    public function deleted(HotelReturn $hotelReturn)
    {
        //
    }

    /**
     * Handle the HotelReturn "restored" event.
     *
     * @param  \App\Models\HotelReturn  $hotelReturn
     * @return void
     */
    public function restored(HotelReturn $hotelReturn)
    {
        //
    }

    /**
     * Handle the HotelReturn "force deleted" event.
     *
     * @param  \App\Models\HotelReturn  $hotelReturn
     * @return void
     */
    public function forceDeleted(HotelReturn $hotelReturn)
    {
        //
    }
}
