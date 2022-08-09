<?php

namespace App\Http\Livewire\Returns;

use App\Models\BusinessTaxType;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Traits\SevenDaysPenaltyTrait;

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
        $this->taxTypeCurrency = BusinessTaxType::where('business_id', $this->return->business_id)->where('tax_type_id', $this->return->tax_type_id)->value('currency');

        if ($this->modelName == "App\Models\Returns\MmTransferReturn" || $this->modelName == "App\Models\Returns\EmTransactionReturn") {

            if($financialMonth = SevenDaysPenaltyTrait::getFilingMonthSevenDays($this->businessLocationId, $this->modelName)){

                $this->penalties = SevenDaysPenaltyTrait::getTotalPenaltiesSevenDays($financialMonth, $this->return->total_amount_due, $this->taxTypeCurrency);
            }

        } else {

            if($financialMonth = $this->getFilingMonth($this->businessLocationId, $this->modelName)){

                $this->penalties = $this->getTotalPenalties($financialMonth, $this->return->total_amount_due, $this->taxTypeCurrency);
            }
        }
    }

    


    public function render()
    {
        return view('livewire.returns.returns-penalty');
    }
}
