<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Region;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name;
    public $region;
    public $location;
    public $old_values;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions,name,' . $this->region->id . ',id',
            'location' => 'required'
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-region-edit')) {
            abort(403);
        }

        $this->validate();
        if ($this->region->is_approved == DualControl::NOT_APPROVED) {
            $this->alert('error', DualControl::UPDATE_ERROR_MESSAGE);
            return;
        }
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'location' => $this->location
            ];

            $this->triggerDualControl(get_class($this->region), $this->region->id, DualControl::EDIT, 'editing region '.$this->region->name, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.region.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('success', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.region.index');
        }
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->region = Region::findOrFail($id);
        $this->name = $this->region->name;
        $this->location = $this->region->location;
        $this->old_values = [
            'name' => $this->name,
            'location' => $this->location
        ];
    }

    public function render()
    {
        return view('livewire.region-edit-modal');
    }
}
