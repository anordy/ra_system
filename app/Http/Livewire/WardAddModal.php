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

class WardAddModal extends Component
{

    use LivewireAlert;

    public $regions = [];
    public $districts = [];
    public $region_id;
    public $district_id;
    public $name;

    protected $rules = [
        'region_id' => 'required',
        'district_id' => 'required',
        'name' => 'required',
    ];

    public function mount()
    {
        $this->regions = Region::all();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->select('id', 'name')->get();
        }

    }


    public function submit()
    {
        if (!Gate::allows('setting-ward-add')) {
            abort(403);
        }

        $this->validate();
        try {
            Ward::create([
                'name' => $this->name,
                'district_id' => $this->district_id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);

            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.ward-add-modal');
    }
}
