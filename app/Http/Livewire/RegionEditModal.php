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


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions,name,'.$this->region->id.',id',
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
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $data = Region::find($id);
        $this->region = $data;
        $this->name = $data->name;
    }

    public function render()
    {
        return view('livewire.region-edit-modal');
    }
}
