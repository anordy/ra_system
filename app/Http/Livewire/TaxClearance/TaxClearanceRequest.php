<?php

namespace App\Http\Livewire\TaxClearance;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use Livewire\Component;

class TaxClearanceRequest extends Component
{
    public $debts;
    public $taxClearence;

    public function mount($debts, $taxClearence){
        $this->debts = $debts;
        $this->taxClearence = $taxClearence;
    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
