<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\BusinessTaxType;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReturnsPortPenalty extends Component
{
    use PenaltyTrait, LivewireAlert;
    public $businessLocationId;
    public $modelName;
    public $financialMonth;
    public $penalties_tzs, $penalties_usd;
    public $modelId;
    public $taxTypeCurrency;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->return = $modelName::findOrFail($modelId);
        $this->businessLocationId = $this->return->business_location_id;
        $this->taxTypeCurrency = BusinessTaxType::where('business_id', $this->return->business_id)->where('tax_type_id', $this->return->tax_type_id)->value('currency');

        if ($financialMonth = $this->getFilingMonth($this->businessLocationId, $this->modelName)) {
            // Tunadeal na wewe perperndicular
            if ($this->return->total_vat_payable_usd > 0) {
                $this->penalties_tzs = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_tzs, $this->taxTypeCurrency);
                $this->penalties_usd = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_usd, 'USD');
            } else {
                $this->penalties_tzs = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_tzs, $this->taxTypeCurrency);
            }

        } else {
            // Kama hana, pamoja
            $this->penalties_tzs = [];
            $this->penalties_usd = [];
        }
    }

    public function render()
    {
        return view('livewire.returns.port.returns-port-penalty');
    }
}
