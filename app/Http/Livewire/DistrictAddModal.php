<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $region_id;
    public $regions = [];


    protected function rules()
    {
        return [
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|min:2|unique:districts',
        ];
    }

    public function mount()
    {
        $this->regions = Region::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-district-add')) {
            abort(403);
        }

        $this->validate();
        try {
            District::create([
                'name' => $this->name,
                'region_id' => $this->region_id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);

            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.district-add-modal');
    }
}
