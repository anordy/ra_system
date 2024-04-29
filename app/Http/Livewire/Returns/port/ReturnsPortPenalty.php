<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\BusinessTaxType;
use App\Traits\PenaltyTrait;
use App\Traits\CustomAlert;
use Livewire\Component;

class ReturnsPortPenalty extends Component
{
    use PenaltyTrait, CustomAlert;
    public $businessLocationId;
    public $modelName;
    public $financialMonth;
    public $penalties_tzs, $penalties_usd;
    public $modelId;
    public $taxTypeCurrency;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->return = $modelName::findOrFail($this->modelId);
        $this->businessLocationId = $this->return->business_location_id;
        $this->taxTypeCurrency = BusinessTaxType::select('currency')->where('business_id', $this->return->business_id)->where('tax_type_id', $this->return->tax_type_id)->value('currency');

        if ($financialMonth = $this->getFilingMonth($this->businessLocationId, $this->modelName)) {
            if ($this->return->total_vat_payable_usd > 0) {
                $this->penalties_tzs = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_tzs, $this->taxTypeCurrency);
                $this->penalties_usd = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_usd, 'USD');
            } else {
                $this->penalties_tzs = $this->getTotalPenalties($financialMonth, $this->return->total_vat_payable_tzs, $this->taxTypeCurrency);
            }

        } else {
            $this->penalties_tzs = [];
            $this->penalties_usd = [];
        }
    }

    public function render()
    {
        return view('livewire.returns.port.returns-port-penalty');
    }
}
