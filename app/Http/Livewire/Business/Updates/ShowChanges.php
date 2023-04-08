<?php

namespace App\Http\Livewire\Business\Updates;

use App\Models\AccountType;
use App\Models\Bank;
use Livewire\Component;
use App\Models\BusinessActivity;
use App\Models\BusinessUpdate;
use App\Models\Currency;
use App\Models\District;
use App\Models\Region;
use App\Models\Street;
use App\Models\Taxpayer;
use App\Models\Ward;
use App\Traits\CustomAlert;

class ShowChanges extends Component
{

    use CustomAlert;

    public $business;
    public $business_update;
    private $old_values;
    private $new_values;
    public $business_id;
    public $agent_contract;

    public function mount($updateId)
    {
        $this->business_update = BusinessUpdate::find(decrypt($updateId));
        if(is_null($this->business_update)){
            abort(404);
        }
        $this->old_values = json_decode($this->business_update->old_values);
        $this->business_id = $this->business_update->business_id;
        $this->new_values = json_decode($this->business_update->new_values);
        $this->agent_contract = $this->business_update->agent_contract ?? null;
    }

    public function getNameById($type, $id)
    {
        if ($type == 'business_activities_type_id') {
            return BusinessActivity::find($id)->name ?? 'N/A';
        } else if ($type == 'currency_id') {
            return Currency::find($id)->name ?? 'N/A';
        } else if ($type == 'region_id') {
            return Region::find($id)->name ?? 'N/A';
        } else if ($type == 'district_id') {
            return District::find($id)->name ?? 'N/A';
        } else if ($type == 'ward_id') {
            return Ward::find($id)->name ?? 'N/A';
        } else if ($type == 'bank_id') {
            return Bank::find($id)->name ?? 'N/A';
        } else if ($type == 'account_type_id') {
            return AccountType::find($id)->name ?? 'N/A';
        } else if ($type == 'street_id') {
            return Street::find($id)->name ?? 'N/A';
        }
    }

    public function getResponsiblePersonNameById($id)
    {
        return Taxpayer::find($id)->fullname() ?? 'N/A';
    }

    public function getResponsiblePersonNameByReferenceNo($refNo)
    {
        return Taxpayer::where('reference_no', $refNo)->first()->fullname() ?? 'N/A';
    }

    public function render()
    {
        return view('livewire.business.updates.show', ['new_values' => $this->new_values, 'old_values' => $this->old_values]);
    }
}
