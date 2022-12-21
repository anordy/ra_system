<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\Region;
use App\Models\Ward;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class WardEditModal extends Component
{

    use LivewireAlert;

    public $regions = [];
    public $districts = [];
    public $region_id;
    public $district_id;
    public $name;
    public $ward;

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
        try {
            $this->ward->update([
                'name' => $this->name,
                'district_id' => $this->district_id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);

            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.ward-edit-modal');
    }
}
