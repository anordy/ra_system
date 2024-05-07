<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchTaxpayerAccount extends Component
{
    use CustomAlert;

    public $businessName, $businesses, $accounts;


    public function rules()
    {
        return [
            'businessName' => 'required'
        ];
    }


    public function mount()
    {

    }

    public function search()
    {
        // Search business name/z-number in businesses then get all associated tax types
        $this->businesses = BusinessLocation::select('id', 'name', 'business_id')
            ->whereHas('business', function ($query) {
                $query->whereRaw(DB::raw("LOWER(name) like '%' || LOWER('$this->businessName') || '%'"));
                $query->OrWhereRaw(DB::raw("LOWER(taxpayer_name) like '%' || LOWER('$this->businessName') || '%'"));
            })
            ->where('status', BusinessStatus::APPROVED)
            ->get();

        // Check if business has any tax types
        // Property tax, motor vehicle, drivers-license
        $this->accounts = TaxpayerLedger::with(['location', 'taxtype'])
            ->whereIn('business_location_id', $this->businesses->pluck('id')->toArray())
            ->get();

        // Or Search taxpayer name


    }


    public function submit()
    {
        $this->validate();

        // Take the business id and search into taxpayer ledgers and return accounts

    }

    public function clear()
    {
        $this->businessName = null;
        $this->businesses = [];
    }

    public function render()
    {
        return view('livewire.taxpayer-ledger.search');
    }
}
