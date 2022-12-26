<?php

namespace App\Http\Livewire;

use App\Models\Region;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionEditModal extends Component
{

    use LivewireAlert;
    public $name;
    public $region;
    public $location;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions,name,'.$this->region->id.',id',
            'location' => 'required'
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-region-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->region->update([
                'name' => $this->name,
                'location' => $this->location
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function mount($id)
    {
        $data = Region::find($id);
        $this->region = $data;
        $this->name = $data->name;
        $this->location = $data->location;
    }

    public function render()
    {
        return view('livewire.region-edit-modal');
    }
}
