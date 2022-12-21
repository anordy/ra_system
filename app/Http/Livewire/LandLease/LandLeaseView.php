<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Gate;

class LandLeaseView extends Component
{
    use LivewireAlert, PaymentsTrait;
    public $landLease;
    public $taxType;
    public $leasePayment;

    //mount function
    public function mount($enc_id)
    {
        $this->landLease = LandLease::find(decrypt($enc_id));
        $this->taxType = TaxType::where('code', TaxType::LAND_LEASE)->first();
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-view');
    }

    public function controlNumber()
    {

        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }

        DB::beginTransaction();

        try {
            $billitems = [
                [
                    'billable_id' => $this->landLease->id,
                    'billable_type' => get_class($this->landLease),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->landLease->payment_amount,
                    // 'amount' => 2242,
                    'currency' => 'USD',
                    'gfs_code' => $this->taxType->gfs_code,
                    // 'gfs_code' => "12432",
                    'tax_type_id' => $this->taxType->id,
                    'fee_id' => null,
                    'fee_type' => null,
                ],
            ];
            $isRegistered = $this->landLease->is_registered;
            $taxpayer = $isRegistered ? $this->landLease->taxpayer : null;

            $payer_type = get_class($taxpayer == null ? $this->landLease->createdBy : $taxpayer);
            $payer_name = $isRegistered ? implode(" ", array($taxpayer->first_name, $taxpayer->last_name)) : $this->landLease->name;
            $payer_email = $isRegistered ? $taxpayer->email : $this->landLease->email;
            $payer_phone = $isRegistered ? $taxpayer->mobile : $this->landLease->phone;
            $description = "Payment for Land Lease for " . ($isRegistered ? implode(" ", array($taxpayer->first_name, $taxpayer->last_name)) : $this->landLease->name);
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = 'USD';
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = 1;
            $payer_id = $isRegistered ? $taxpayer->id : $this->landLease->createdBy->id;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billableId = $this->landLease->id;
            $billableType = get_class($this->landLease);

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $this->taxType->id,
                $payer_id,
                $payer_type,
                $payer_name,
                $payer_email,
                $payer_phone,
                $expire_date,
                $description,
                $payment_option,
                $currency,
                $exchange_rate,
                $createdby_id,
                $createdby_type,
                $billitems
            );

            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    $this->landLease->status = 'control-number-generating';
                    $this->landLease->save();
                    $this->alert('success', 'Request sent successfully.');
                } else {
                    $this->landLease->status = 'control-number-generating-failed';
                    $this->landLease->save();
                    $this->alert('error', 'Failed to Generate Control Number');
                }
            } else {
                // We are local
                $this->landLease->status = 'control-number-generated';
                $this->landLease->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();

                $this->alert('success', 'Control Number generated successfully.');
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?.');
        }
    }

    public function regenerate(){
        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }
        $response = $this->regenerateControlNo($this->return->bill);
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect()->back()->getTargetUrl();
        }
        $this->alert('error', 'Control number could not be generated, please try again later.');
    }
}
