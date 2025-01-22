<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Enum\Currencies;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\TransactionType;
use App\Models\BusinessLocation;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BusinessLocationLedger extends Component
{
    use CustomAlert;

    public $ledgers = [], $summations = [], $businessLocationId;

    public function mount($businessLocationId){
        $this->businessLocationId = $businessLocationId;
        $this->loadData();
    }

    public function loadData() {
        try {

            $this->tzsOpeningFigures = [];

            $tzsLedgers = TaxpayerLedger::query()->select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at', 'debit_no')
                ->where('business_location_id', $this->businessLocationId)
                ->where('currency', Currencies::TZS)
                ->orderBy('source_type', 'ASC')
                ->orderBy('source_id', 'ASC')
                ->orderBy('transaction_date', 'ASC')
                ->orderByRaw("CASE TRANSACTION_TYPE WHEN 'DEBIT' THEN 1 WHEN 'CREDIT' THEN 2 END ASC");

            $this->usdOpeningFigures = [];

            $usdLedgers = TaxpayerLedger::query()->select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at', 'debit_no')
                ->where('business_location_id', $this->businessLocationId)
                ->where('currency', Currencies::USD)
                ->orderBy('source_type', 'ASC')
                ->orderBy('source_id', 'ASC')
                ->orderBy('transaction_date', 'ASC')
                ->orderByRaw("CASE TRANSACTION_TYPE WHEN 'DEBIT' THEN 1 WHEN 'CREDIT' THEN 2 END ASC");

            $this->locationName = BusinessLocation::findOrFail($this->businessLocationId, ['name'])->name;

            $ledgers = [
                'USD' => $usdLedgers->get(),
                'TZS' => $tzsLedgers->get()
            ];

            $tzsCreditSum = $ledgers['TZS']->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $tzsDebitSum = $ledgers['TZS']->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;
            $usdCreditSum = $ledgers['USD']->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $usdDebitSum = $ledgers['USD']->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;

            $this->summations = [
                'TZS' => ['credit' => $tzsCreditSum, 'debit' => $tzsDebitSum],
                'USD' => ['credit' => $usdCreditSum, 'debit' => $usdDebitSum],
            ];

            $this->ledgers = $ledgers;
        } catch (\Exception $exception) {
            Log::error('TAXPAYER-LEDGER-LOAD-DATA', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
    }

    public function render(){
        return view('livewire.taxpayer-ledger.business-location-ledger');
    }
}