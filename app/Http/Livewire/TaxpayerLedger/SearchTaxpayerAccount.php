<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use App\Traits\CustomAlert;
use Livewire\Component;

class SearchTaxpayerAccount extends Component
{
    use CustomAlert;

    public $businessName, $businesses;


    public function rules()
    {
        return [
            'businessName' => 'required'
        ];
    }


    public function mount()
    {

    }

    public function search() {
        // Search business name/z-number in businesses then get all associated tax types
        $this->businesses = Business::with(['taxTypes'])
            ->select('id', 'name', 'taxpayer_name')
            ->where('name', 'like', "%{$this->businessName}%")
            ->where('status', BusinessStatus::APPROVED)
            ->get();

        // Check if business has any tax types
        // Property tax, motor vehicle, drivers-license


        // Or Search taxpayer name


    }



    public function submit(){
        $this->validate();

        // Take the business id and search into taxpayer ledgers

    }

    public function clear() {
        $this->businessName = null;
        $this->businesses = [];
    }

    public function render()
    {
        return view('livewire.taxpayer-ledger.search');
    }
}
