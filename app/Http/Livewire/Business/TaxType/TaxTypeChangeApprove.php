<?php

namespace App\Http\Livewire\Business\TaxType;

use App\Models\BusinessTaxTypeChange;
use App\Models\TaxType;
use Livewire\Component;
use App\Traits\CustomAlert;

class TaxTypeChangeApprove extends Component
{

    use CustomAlert;

    public $taxchange;

    public function mount($taxchangeId)
    {
        $taxchange = BusinessTaxTypeChange::find(decrypt($taxchangeId));
        if (is_null($taxchange)){
            abort(404);
        }
        $this->taxchange = $taxchange;
    }

    public function render()
    {
        return view('livewire.business.taxtype.change-approve');
    }
}
