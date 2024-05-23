<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Enum\Currencies;
use App\Enum\ReportStatus;
use App\Enum\TransactionType;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchTaxpayerAccount extends Component
{
    use CustomAlert;

    public $businessName, $accounts = [], $ztnNumber, $taxTypes = [], $taxTypeId;
    public $referenceNumber, $ledgers = [], $showLedgers = false, $startDate, $endDate;


    public function rules()
    {
        return [
            'businessName' => !is_null($this->businessName) || !is_null($this->ztnNumber) ? 'nullable' : 'required|alpha_gen',
            'ztnNumber' => !is_null($this->businessName) || !is_null($this->ztnNumber) ? 'nullable' : 'required|alpha_gen',
            'taxTypeId' => 'nullable|alpha_gen',
            'referenceNumber' => 'nullable|alpha_num',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after:startDate',
        ];
    }


    public function mount()
    {
        $this->taxTypes = TaxType::main()->select('id', 'name')->get();
        $this->taxTypeId = ReportStatus::All;
    }

    public function updated($propertyName) {
        if ($propertyName === 'taxTypeId') {
            $this->accounts = [];
        }
    }

    public function search()
    {
        $this->validate();

        $businessNameLength = strlen($this->businessName);

        if ($businessNameLength >= 3) {
            $this->getAccounts();
        } else {
            $this->accounts = [];
        }

    }

    public function getAccounts() {
        $locationQuery = BusinessLocation::select('id', 'name', 'business_id')
            ->where('status', BusinessStatus::APPROVED);

        if ($this->businessName) {
            // Search business name/z-number in businesses then get all associated tax types
            $locationQuery->whereRaw(DB::raw("LOWER(name) like '%' || LOWER('$this->businessName') || '%'"));
        }

        if ($this->ztnNumber) {
            $locationQuery->where('zin', trim($this->ztnNumber));
        }

        $businessLocationIds = $locationQuery->get()->pluck('id')->toArray();

        if ($this->taxTypeId != ReportStatus::All) {
            $ledgerQuery = TaxpayerLedger::with(['location', 'taxtype'])
                ->select('tax_type_id', 'business_location_id')
                ->whereIn('business_location_id', $businessLocationIds);

            $ledgerQuery->where('tax_type_id', $this->taxTypeId);

            // TODO: Get by limiting results
            $this->accounts = $ledgerQuery->groupBy('tax_type_id', 'business_location_id')
                ->get();
        } else {
            $ledgerQuery = TaxpayerLedger::with(['location', 'taxtype'])
                ->select('business_location_id')
                ->whereIn('business_location_id', $businessLocationIds);

            // TODO: Get by limiting results
            $this->accounts = $ledgerQuery->groupBy('business_location_id')
                ->get();
        }

        if (!$this->accounts) {
            $this->customAlert('warning', 'No results found');
        }
    }


    public function getLedgers($businessLocationId, $taxTypeId) {

        $this->tzsOpeningFigures = [];

        $tzsLedgers = TaxpayerLedger::query()->select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at')
            ->where('business_location_id', $businessLocationId)
            ->where('currency', Currencies::TZS)
            ->orderBy('source_type', 'DESC')
            ->orderBy('source_id', 'DESC')
            ->orderBy('transaction_date', 'ASC')
            ->orderByRaw("CASE TRANSACTION_TYPE WHEN 'DEBIT' THEN 1 WHEN 'CREDIT' THEN 2 END ASC");

        if ($this->startDate && $this->endDate) {
            $tzsLedgersOpeningQuery = clone $tzsLedgers;
            $query = $tzsLedgersOpeningQuery->whereDate('transaction_date', '<', $this->startDate)->get();
            $debit = $query->where('transaction_type', TransactionType::DEBIT)->sum('total_amount');
            $credit = $query->where('transaction_type', TransactionType::CREDIT)->sum('total_amount');
            $this->tzsOpeningFigures = ['debit' => $debit, 'credit' => $credit];
            $tzsLedgers->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        $this->usdOpeningFigures = [];

        $usdLedgers = TaxpayerLedger::query()->select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at')
            ->where('business_location_id', $businessLocationId)
            ->where('currency', Currencies::USD)
            ->orderBy('source_type', 'DESC')
            ->orderBy('source_id', 'DESC')
            ->orderBy('transaction_date', 'ASC')
            ->orderByRaw("CASE TRANSACTION_TYPE WHEN 'DEBIT' THEN 1 WHEN 'CREDIT' THEN 2 END ASC");

        if ($this->startDate && $this->endDate) {
            $usdLedgersOpeningQuery = clone $usdLedgers;
            $query = $usdLedgersOpeningQuery->whereDate('transaction_date', '<', $this->startDate)->get();
            $debit = $query->where('transaction_type', TransactionType::DEBIT)->sum('total_amount');
            $credit = $query->where('transaction_type', TransactionType::CREDIT)->sum('total_amount');
            $this->usdOpeningFigures = ['debit' => $debit, 'credit' => $credit];
            $usdLedgers->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        $this->locationName = BusinessLocation::findOrFail($businessLocationId, ['name'])->name;

        if ($taxTypeId === ReportStatus::All) {
            $this->taxTypeName = 'All Tax Types';
        } else {
            $tzsLedgers->where('tax_type_id', $taxTypeId);
            $usdLedgers->where('tax_type_id', $taxTypeId);
            $this->taxTypeName = TaxType::findOrFail($taxTypeId, ['name'])->name;
        }

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

        $this->showLedgers = true;

    }


    public function clear()
    {
        $this->businessName = null;
        $this->ztnNumber = null;
        $this->accounts = [];
        $this->ledgers = [];
    }

    public function back() {
        $this->showLedgers = false;
        $this->getAccounts();
    }

    public function render()
    {
        return view('livewire.taxpayer-ledger.search');
    }
}
