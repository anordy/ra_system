<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchTaxpayerAccount extends Component
{
    use CustomAlert;

    public $businessName, $accounts = [], $ztnNumber, $taxTypes = [], $taxTypeId;
    public $referenceNumber;


    public function rules()
    {
        return [
            'businessName' => 'nullable|alpha_gen',
            'ztnNumber' => 'nullable|alpha_gen',
            'taxTypeId' => 'nullable|alpha_gen',
            'referenceNumber' => 'nullable|alpha_num'
        ];
    }


    public function mount()
    {
        $this->taxTypes = TaxType::main()->select('id', 'name')->get();
    }

    public function updated($propertyName) {
        if ($propertyName === 'taxTypeId') {
            $this->accounts = [];
        }
    }

    public function search()
    {
        $this->validate();

        if (!$this->businessName && !$this->ztnNumber) {
            session()->flash('error', 'Please enter search identifier information e.g. Business name or ZTN Number');
            return back();
        }

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

        $ledgerQuery = TaxpayerLedger::with(['location', 'taxtype'])
            ->select('tax_type_id', 'business_location_id')
            ->whereIn('business_location_id', $businessLocationIds);

        if ($this->taxTypeId) {
            $ledgerQuery->where('tax_type_id', $this->taxTypeId);
        }

        // TODO: Get by limiting results
        $this->accounts = $ledgerQuery->groupBy('tax_type_id', 'business_location_id')
            ->get();

        if (!$this->accounts) {
            $this->customAlert('warning', 'No results found');
        }

    }


    public function clear()
    {
        $this->businessName = null;
        $this->ztnNumber = null;
        $this->accounts = [];
    }

    public function render()
    {
        return view('livewire.taxpayer-ledger.search');
    }
}
