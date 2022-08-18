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
    public $returnDebts;
    public $verificationDebts;
    public $auditDebts;
    public $investigationDebts;
    public $taxClearence;

    public function mount($taxClearence, $returnDebts, $verificationDebts, $auditDebts, $investigationDebts){
        $this->returnDebts = $returnDebts;
        $this->verificationDebts = $verificationDebts;
        $this->auditDebts = $auditDebts;
        $this->investigationDebts = $investigationDebts;
        $this->taxClearence = $taxClearence;


    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
