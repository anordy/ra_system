<?php

namespace App\Http\Livewire\PropertyTax;

use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Models\Currency;
use App\Models\PropertyTax\PropertyPayment;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PropertyTaxTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PropertyTaxPayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse, PropertyTaxTrait;

    public $payment, $viableFinancialYear;

    public function mount($payment)
    {
        $this->payment = $payment;
        $this->viableFinancialYear = $this->getPayableFinancialYear($this->payment->property_id);
    }

    public function generateFinancialYearPayment() {
        try {
            $amount = $this->payment->amount ?? GeneralConstant::ZERO_INT;

            if (!$amount || $amount <= GeneralConstant::ZERO_INT) {
                $this->customAlert('warning', 'Property payment does not have amount');
                return;
            }

            $dueDate = Carbon::now()->endOfYear();

            if ($dueDate->lt(Carbon::now())) {
                $dueDate = $dueDate->addMonth();
            }

            $propertyPayment = PropertyPayment::create([
                'property_id' => $this->payment->property_id,
                'financial_year_id' => $this->viableFinancialYear->id,
                'currency_id' => Currency::select('id')->where('iso', 'TZS')->firstOrFail()->id,
                'amount' => $amount,
                'interest' => GeneralConstant::ZERO_INT,
                'total_amount' => $amount,
                'payment_date' => $dueDate,
                'curr_payment_date' => $dueDate,
                'payment_status' => BillStatus::SUBMITTED,
                'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
            ]);

            if (!$propertyPayment) throw new Exception('Failed to create property payment');

            $this->generatePropertyTaxControlNumber($propertyPayment);

            session()->flash('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));

        } catch (Exception $exception) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('GENERATE-FINANCIAL-YEAR', [$exception]);
        }
    }


    public function refresh()
    {
        $this->payment = get_class($this->payment)::find($this->payment->id);
        if (is_null($this->payment)) {
            abort(404);
        }
    }

    public function regenerate()
    {
        $response = $this->regenerateControlNo($this->payment->bill);
        if ($response) {
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill()
    {
        try {
            $this->generatePropertyTaxControlNumber($this->payment);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render()
    {
        return view('livewire.property-tax.property-tax-payment');
    }
}
