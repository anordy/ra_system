<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Investigation\TaxInvestigationLocation;
use App\Models\Investigation\TaxInvestigationTaxType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeclaredSalesAnalysisInstances extends Component
{
    use LivewireAlert;

    public $investigation;
    public $locations;
    public $taxTypes;

    public function mount($investigation)
    {

        $this->investigation = $investigation;
        $this->locations = TaxInvestigationLocation::where('tax_investigation_id', $investigation->id)->get();
        $this->taxTypes = TaxInvestigationTaxType::where('tax_investigation_id', $investigation->id)->get();
    }
    public function render()
    {
        return view('livewire.investigation.declared-sales-analysis-instances');
    }
}
