<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Models\LandLease;
use App\Models\LandLeaseFiles;
use App\Models\LeasePayment;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Gate;

class LandLeaseView extends Component
{
    use CustomAlert, PaymentsTrait;

    public $landLease;
    public $taxType;
    public $leasePayment;
    public $unpaidLease;
    public $advancePaymentStatus;
    public const ADVANCE_PAYMENT_MAX_YEARS = 3;

    //mount function
    public $leaseDocuments;
    /**
     * @var string
     */
    public $dueDate;
    public $leaseLastPaid;

    public function mount($enc_id)
    {

        $this->landLease = LandLease::find(decrypt($enc_id));
        if (is_null($this->landLease)) {
            abort(404);
        }
        $this->leaseDocuments = $this->leaseDocuments($this->landLease->id);
        $statuses = [
            LeaseStatus::IN_ADVANCE_PAYMENT,
            LeaseStatus::LATE_PAYMENT,
            LeaseStatus::ON_TIME_PAYMENT,
            LeaseStatus::COMPLETE
        ];

        $this->taxType = TaxType::where('code', TaxType::LAND_LEASE)->first();
        $this->unpaidLease = LeasePayment::where('land_lease_id', $this->landLease->id)->whereNotIn('status', $statuses)->exists();

        //check for advance payment > 3 years
        if ($this->landLease->rent_commence_date > now()->addYears(self::ADVANCE_PAYMENT_MAX_YEARS)) {
            $this->advancePaymentStatus = true;
        } else {
            $this->advancePaymentStatus = false;
        }

        $this->dueDate = $this->getDueDate();
        //$this->leaseLastPaid = $this->getLastLeasePayment();
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-view');
    }

    public function controlNumber()
    {

        if (!Gate::allows('land-lease-generate-control-number')) {
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
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
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
                    $this->customAlert('success', 'Request sent successfully.');
                } else {
                    $this->landLease->status = 'control-number-generating-failed';
                    $this->landLease->save();
                    $this->customAlert('error', 'Failed to Generate Control Number');
                }
            } else {
                // We are local
                $this->landLease->status = 'control-number-generated';
                $this->landLease->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->save();

                $this->customAlert('success', 'Control Number generated successfully.');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
        }
    }

    public function regenerate()
    {
        if (!Gate::allows('land-lease-generate-control-number')) {
            abort(403);
        }
        $response = $this->regenerateControlNo($this->return->bill);
        if ($response) {
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect()->back()->getTargetUrl();
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    public function getDueDate(): string
    {
        $rentCommenceDate = new DateTime($this->landLease->rent_commence_date);
        return $rentCommenceDate->add(new DateInterval('P' . (int)$this->landLease->valid_period_term . 'Y'))
            ->format('d F Y');
    }

//    public function getLastLeasePayment()
//    {
//        return LeasePayment::where('land_lease_id', $this->landLease->id)->orderBy('id', 'desc')->first()->paid_at;
//    }

    public function leaseDocuments($id)
    {
        return LandLeaseFiles::select('name','file_path')->where('land_lease_id', $id)->get();
    }
}
