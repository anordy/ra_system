<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\Region;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictEditModal extends Component
{

    use LivewireAlert;
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
        try {
            $this->district->update([
                'name' => $this->name,
                'region_id' => $this->region_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function mount($id)
    {

        $this->regions = Region::all();

        $data = District::find($id);
        $this->district = $data;
        $this->name = $data->name;
        $this->region_id = $data->region_id;
    }

    public function render()
    {
        return view('livewire.district-edit-modal');
    }
}
