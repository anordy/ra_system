<?php

namespace App\Http\Livewire\Audit;

use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Traits\CustomAlert;
use Livewire\Component;

class DeclaredSalesAnalysisInstances extends Component
{
    use CustomAlert;

    public $audit;
    public $locations;
    public $taxTypes;

    public function mount($auditId)
    {

        $audit = TaxAudit::find(decrypt($auditId));
        if (is_null($audit)){
            abort(404);
        }
        $this->audit = $audit;
        $this->locations = $audit->businessLocations;
        $this->taxTypes = $audit->taxTypes;
    }

    public function render()
    {
        return view('livewire.audit.declared-sales-analysis-instances');
    }
}
