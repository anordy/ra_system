<?php

namespace App\Http\Livewire\Audit;

use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeclaredSalesAnalysisInstances extends Component
{
    use LivewireAlert;

    public $audit;
    public $locations;
    public $taxTypes;

    public function mount($audit)
    {

        $this->audit = $audit;
        $this->locations = $audit->businessLocations;
        $this->taxTypes = $audit->taxTypes;
    }

    public function render()
    {
        return view('livewire.audit.declared-sales-analysis-instances');
    }
}
