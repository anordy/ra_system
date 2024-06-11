<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\LandLeaseHistory;
use App\Models\TaxPayer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AssignTaxpayer extends Component
{
    use CustomAlert;

    public $landLease;
    public $zrbNumber;
    public $taxpayerId;

    public $showTaxpayerDetails;
    public $taxpayerName;

    //mount function
    public function mount($enc_id)
    {
        $this->landLease = LandLease::find(decrypt($enc_id));
    }

    public function render()
    {
        return view('livewire.land-lease.assign-taxpayer');
    }

    protected $rules = [
        'zrbNumber' => 'required|strip_tag|exists:taxpayers,reference_no',
    ];

    protected $messages = [
        'zrbNumber.exists' => 'The ZRA reference Number is invalid',
    ];

    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $landLeaseHistory = LandLeaseHistory::create([
                'land_lease_id'=>$this->landLease->id,
                'is_registered'=>$this->landLease->is_registered,
                'taxpayer_id' => $this->landLease->taxpayer_id,
                'dp_number' => $this->landLease->dp_number,
                'commence_date' => $this->landLease->commence_date,
                'payment_month' => $this->landLease->payment_month,
                'payment_amount' => $this->landLease->payment_amount,
                'review_schedule' => $this->landLease->review_schedule,
                'valid_period_term' => $this->landLease->valid_period_term,
                'region_id' => $this->landLease->region_id,
                'district_id' => $this->landLease->district_id,
                'ward_id' => $this->landLease->ward_id,
                'created_by' => $this->landLease->created_by,
                'edited_by' => $this->landLease->edited_by,
                'category' => $this->landLease->category,
                'name' => $this->landLease->name,
                'email' => $this->landLease->email,
                'phone' => $this->landLease->phone,
                'address' => $this->landLease->address,
                'lease_agreement_path' => $this->landLease->lease_agreement_path,
            ]);

            $this->landLease->taxpayer_id = $this->taxpayerId;
            $this->landLease->is_registered = true;
            $this->landLease->save();
            DB::commit();

            //redirect to route "land-lease.index"
            $this->flash('success', __('Edited successfully'));
            return redirect()->route("land-lease.list");

        } catch (\Exception$e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->flash('error', __('Failed to edit Lease'));
        }
    }
    public function updated($propertyName)
    {
        if ($propertyName === 'zrbNumber') {
            $taxPayer = TaxPayer::where('reference_no', $this->zrbNumber)->first();
            if ($taxPayer) {
                $this->taxpayerId = $taxPayer->id;
                $this->taxpayerName = $taxPayer->first_name . ' ' . $taxPayer->last_name;
                $this->showTaxpayerDetails = true;
            } else {
                $this->taxpayerId = null;
                $this->showTaxpayerDetails = false;
                $this->taxpayerName = '';
            }
        }
    }

}
