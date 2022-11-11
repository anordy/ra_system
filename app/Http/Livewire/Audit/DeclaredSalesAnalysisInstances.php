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
        $this->locations = TaxAuditLocation::where('tax_audit_id', $audit->id)->get();
        $this->taxTypes = TaxAuditTaxType::where('tax_audit_id', $audit->id)->get();
    }

    public function render()
    {
        return view('livewire.audit.declared-sales-analysis-instances');
    }
}
