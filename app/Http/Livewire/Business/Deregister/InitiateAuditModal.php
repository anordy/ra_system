<?php

namespace App\Http\Livewire\Business\Deregister;

use Exception;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessDeregistration;
use App\Models\BusinessLocation;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Models\TaxAudit\TaxAuditLocation;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InitiateAuditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $business;
    public $business_id;
    public $location_ids;
    public $tax_type_ids;
    public $intension;
    public $scope;
    public $period_from;
    public $period_to;

    public $selectedBusiness;
    public $locations = [];
    public $taxTypes = [];

    public $deregister;


    protected function rules()
    {
        return [
            'business_id' => 'required',
            'location_ids' => 'required',
            'tax_type_ids' => 'required',
            'intension' => 'required',
            'scope' => 'required',
            'period_from' => 'required|date',
            'period_to' => 'required|after:period_from',
        ];
    }

    public function mount($deregister_id)
    {
        $this->deregister = BusinessDeregistration::findOrFail($deregister_id);
        $this->business_id = $this->deregister->business_id;
        $this->business = $this->deregister->business;
        $this->locations = $this->business->locations;
        $this->taxTypes = $this->business->taxTypes;
        $this->tax_type_ids = $this->taxTypes->pluck('id');

        if ($this->deregister->deregistration_type === 'location') {
            $this->location_ids = [$this->deregister->location_id];
        } else {
            $this->location_ids = $this->locations->pluck('id');
        }

    }

    public function submit()
    {
        $check = TaxAudit::where('business_id', $this->business_id)
            ->where('location_id', $this->location_ids)
            ->where('tax_type_id', $this->tax_type_ids)
            ->whereIn('status', ['draft', 'pending'])
            ->first();

        if ($check) {
            $this->validate(
                ['business_id' => 'required'],
                ['business_id.email' => 'Business with the given tax type is already on auditing']
            );
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $taxAudit = TaxAudit::create([
                'business_id' => $this->business_id,
                'location_id' => count($this->location_ids) <= 1 ? $this->location_ids[0] : 0,
                'tax_type_id' => count($this->tax_type_ids) <= 1 ? $this->tax_type_ids[0] : 0,
                'intension' => $this->intension,
                'scope' => $this->scope,
                'period_from' => $this->period_from,
                'period_to' => $this->period_to,
                'created_by_id' => auth()->user()->id,
                'created_by_type' => get_class(auth()->user()),
                'status' => 'draft',
                'origin' => 'manual'
            ]);

            $this->deregister->tax_audit_id = $taxAudit->id;
            $this->deregister->save();

            foreach ($this->location_ids as $location_id) {
                
                TaxAuditLocation::create([
                    'tax_audit_id' => $taxAudit->id,
                    'business_location_id' => $location_id
                ]);
            }

            foreach ($this->tax_type_ids as $tax_type_id) {
                
                TaxAuditTaxType::create([
                    'tax_audit_id' => $taxAudit->id,
                    'business_tax_type_id' => $tax_type_id
                ]);
            }

            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.business.deregister.initiate-audit');
    }
}
