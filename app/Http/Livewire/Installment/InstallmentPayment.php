<?php

namespace App\Http\Livewire\Installment;

use App\Enum\BillStatus;
use App\Enum\InstallmentRequestStatus;
use App\Models\ExchangeRate;
use App\Models\Installment\InstallmentItem;
use App\Models\Installment\InstallmentRequest;
use App\Models\TaxType;
use App\Models\ZmBill;
use App\Services\ZanMalipo\ZmCore;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class InstallmentPayment extends Component
{
    use LivewireAlert, PaymentsTrait, ExchangeRateTrait;

    public $installment;
    public $bill;
    public $activeItem;

    public function mount(){
        $this->activeItem = $this->installment
            ->items()
            ->whereBetween('due_date', [
                Carbon::now()->toDateTimeString(),
                $this->installment->getNextPaymentDate()->toDateTimeString()
            ])
            ->where('status', '!=', BillStatus::COMPLETE)
            ->first();
    }

    public function refresh(){
        $this->activeItem = $this->installment
            ->items()
            ->whereBetween('due_date', [
                Carbon::now()->toDateTimeString(),
                $this->installment->getNextPaymentDate()->toDateTimeString()
            ])
            ->where('status', '!=', BillStatus::COMPLETE)
            ->first();
    }

    public function generateItem(){
        if ($this->activeItem){
            $this->alert('error', 'Control no. already exists!');
        }

        try {
            DB::beginTransaction();
            $item = InstallmentItem::create([
                'installment_id' => $this->installment->id,
                'amount' => $this->installment->amount / $this->installment->installment_count,
                'currency' => $this->installment->currency,
                'due_date' => $this->installment->getNextPaymentDate()->toDateTimeString()
            ]);

            // Generate control no
            $payer_type     = get_class(Auth::user());
            $payer_name     = Auth::user()->fullName;
            $payer_email    = Auth::user()->email;
            $payer_phone    = Auth::user()->mobile;
            $description    = "Installment payment for {$this->installment->business->name} Debt";
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency       = $this->installment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id   = Auth::id();
            $payer_id       = Auth::id();
            $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
            $billableId     = $item->id;
            $billableType   = get_class($item);

            $taxType = TaxType::where('code', TaxType::DEBTS)->firstOrFail();

            $billItems[] = [
                'billable_id' => $item->id,
                'billable_type' => get_class($item),
                'use_item_ref_on_pay' => 'N',
                'amount' => $item->amount,
                'currency' => $item->currency,
                'gfs_code' => $taxType->gfs_code,
                'tax_type_id' => $taxType->id
            ];

            $exchange_rate = $this->getExchangeRate($item->currency);

            $bill = ZmCore::createBill(
                $billableId,
                $billableType,
                $taxType->id,
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
                $billItems
            );

            $this->sendBill($bill, $item);
            DB::commit();

            return redirect()->route('installment.show', encrypt($this->installment->id));
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.installment.installment-payment');
    }
}
