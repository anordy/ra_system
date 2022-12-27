<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;

use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;
    public $name;
    public $region_id;
    public $regions;
    public $district;


    protected function rules()
    {
        return [
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|min:2|unique:regions,name,' . $this->district->id . ',id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-district-edit')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'region_id' => $this->region_id,
            ];
            $this->triggerDualControl(get_class($this->district), $this->district->id, DualControl::EDIT, 'editing district', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function mount($id)
    {

        $this->regions = Region::all();

        $this->district = District::find($id);
        $this->name = $this->district->name;
        $this->region_id = $this->district->region_id;
    }

    public function render()
    {
        return view('livewire.district-edit-modal');
    }
}
