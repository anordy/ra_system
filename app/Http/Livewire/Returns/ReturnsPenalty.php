<?php

namespace App\Http\Livewire\Returns;

use App\Models\BusinessTaxType;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReturnsPenalty extends Component
{
    use PenaltyTrait, LivewireAlert;
    public $businessLocationId;
    public $modelName;
    public $financialMonth;
    public $penalties;
    public $modelId;
    public $taxTypeCurrency;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->return = $modelName::findOrFail($modelId);
        $this->businessLocationId = $this->return->business_location_id;
        $this->taxTypeCurrency = BusinessTaxType::where('business_id', $this->return->business_id)->where('tax_type_id', $this->return->taxtype_id)->value('currency');

        if($financialMonth = $this->getFilingMonth($this->businessLocationId, $this->modelName)){
            // Tunadeal na wewe perperndicular
            $this->penalties = $this->getTotalPenalties($financialMonth, $this->return->total_amount_due, $this->taxTypeCurrency);

        } else {
            // Kama hana, pamoja
            $this->penalties = [];
        }
    }

    


    public function render()
    {
        return view('livewire.returns.returns-penalty');
    }
}
