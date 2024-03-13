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
        try {
            $taxchange = BusinessTaxTypeChange::find(decrypt($taxchangeId));
            if (is_null($taxchange)){
                abort(404);
            }
            $this->taxchange = $taxchange;
        } catch (\Exception $exception){
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator.');
        }
    }

    public function render()
    {
        return view('livewire.business.taxtype.change-approve');
    }
}
