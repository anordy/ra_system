<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Models\Ward;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class WardEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $regions = [];
    public $districts = [];
    public $region_id;
    public $district_id;
    public $name;
    public $ward;
    public $old_values;

    protected $rules = [
        'region_id' => 'required',
        'district_id' => 'required',
        'name' => 'required',
    ];

    public function mount($id)
    {
        $this->regions = Region::select('id', 'name')->get();
        $this->ward = Ward::find($id);
        $this->name = $this->ward->name;
        $this->district_id = $this->ward->district_id;
        $this->region_id = $this->ward->district->region->id;
        $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
        $this->old_values = [
            'name' => $this->name,
            'district_id' => $this->district_id,
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
        }

    }


    public function submit()
    {
        if (!Gate::allows('setting-ward-edit')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'district_id' => $this->district_id,
            ];
            $this->triggerDualControl(get_class($this->ward), $this->ward->id, DualControl::EDIT, 'editing ward', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.ward-edit-modal');
    }
}
