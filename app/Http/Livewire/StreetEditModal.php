<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Models\Street;
use App\Models\Ward;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StreetEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $regions = [];
    public $districts = [];
    public $wards = [];
    public $region_id;
    public $district_id;
    public $ward_id;
    public $name;
    public $street;
    public $old_values;

    protected $rules = [
        'region_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
        'name' => 'required|strip_tag',
    ];

    public function mount($id)
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->street = Street::find(decrypt($id));
        if (!$this->street){
            abort(404, 'Street not found.');
        }
        $this->name = $this->street->name;
        $this->ward_id = $this->street->ward->id;
        $this->district_id = $this->street->ward->district_id;
        $this->region_id = $this->street->ward->district->region->id;
        $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
        $this->wards = Ward::where('district_id', $this->district_id)->select('id', 'name')->get();
        $this->old_values = [
            'name' => $this->name,
            'ward_id' => $this->ward_id,
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
        }

        if ($propertyName === 'district_id'){
            $this->wards = Ward::where('district_id', $this->district_id)->where('is_approved', 1)->select('id', 'name')->get();
        }
    }


    public function submit()
    {
        if (!Gate::allows('setting-street-edit')) {
            abort(403);
        }

        $this->validate();
        if ($this->street->is_approved == DualControl::NOT_APPROVED) {
            $this->alert('error', DualControl::UPDATE_ERROR_MESSAGE);
            return;
        }
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'ward_id' => $this->ward_id,
            ];
            $this->triggerDualControl(get_class($this->street), $this->street->id, DualControl::EDIT, 'editing street '.$this->street->name, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.street.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.street.index');
        }
    }

    public function render()
    {
        return view('livewire.street-edit-modal');
    }
}
