<?php

namespace App\Http\Livewire\TaxClearance;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class TaxClearanceRequest extends Component
{
    public $debts;
    public $taxClearance;

    public function mount($debts, $taxClearance){

        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        $this->debts = $debts;
        $this->taxClearance = $taxClearance;
    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
