<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Investigation\TaxInvestigation;
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

    public function mount($investigationId)
    {
        $investigation = TaxInvestigation::find(decrypt($investigationId));
        if (is_null($investigation)){
            abort(404);
        }
        $this->investigation = $investigation;
        $this->locations = $investigation->businessLocations;
        $this->taxTypes = $investigation->taxTypes;
    }
    public function render()
    {
        return view('livewire.investigation.declared-sales-analysis-instances');
    }
}
