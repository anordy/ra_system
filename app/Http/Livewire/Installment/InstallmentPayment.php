<?php

namespace App\Http\Livewire\Installment;

use App\Enum\BillStatus;
use App\Enum\TransactionType;
use App\Models\Installment\InstallmentItem;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;


class InstallmentPayment extends Component
{
    use CustomAlert, PaymentsTrait, ExchangeRateTrait;

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
            ->first(); // Null value is checked from view.
    }

    public function refresh(){
        $this->activeItem = $this->installment
            ->items()
            ->whereBetween('due_date', [
                Carbon::now()->toDateTimeString(),
                $this->installment->getNextPaymentDate()->toDateTimeString()
            ])
            ->where('status', '!=', BillStatus::COMPLETE)
            ->first(); // Null value is checked from view.
    }

    public function generateItem(){
        if ($this->activeItem){
            $this->customAlert('error', 'Control no. already exists!');
        }
        
        $installmentRequest = $this->installment->request;

        try {
            DB::beginTransaction();
            $item = InstallmentItem::create([
                'installment_id' => $this->installment->id,
                'amount' => roundOff($this->installment->amount / $this->installment->installment_count, $this->installment->installable->currency),
                'currency' => $this->installment->currency,
                'due_date' => $this->installment->getNextPaymentDate()->toDateTimeString()
            ]);

            // Insert ledger
            if (!$this->installment->ledger) {
                $this->recordLedger(
                    TransactionType::DEBIT,
                    get_class($this->installment),
                    $this->installment->id,
                    $this->installment->amount,
                    0,
                    0,
                    $this->installment->amount,
                    $this->installment->tax_type_id,
                    $this->installment->currency,
                    $this->installment->business->taxpayer_id,
                    $this->installment->location_id,
                );
            }

            $payer = $installmentRequest->createdBy;
            
            // Generate control no
            $payer_type     = $installmentRequest->created_by_type;
            $payer_name     = $payer->fullName;
            $payer_email    = $payer->email;
            $payer_phone    = $payer->mobile;
            $description    = "Installment payment for {$this->installment->business->name} Debt";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency       = $this->installment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id   = Auth::id();
            $payer_id       = $payer->id;
            $expire_date    = Carbon::now()->addDays(30)->endOfDay()->toDateTimeString();
            $billableId     = $item->id;
            $billableType   = get_class($item);

            $taxType = TaxType::findOrFail($this->installment->tax_type_id);

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

            DB::commit();
            
            $this->sendBill($bill, $item);

            return redirect()->route('installment.show', encrypt($this->installment->id));
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.installment.installment-payment');
    }
}
