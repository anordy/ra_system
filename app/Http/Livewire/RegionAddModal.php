<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Region;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionAddModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name, $location;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions',
            'location' => 'required',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-region-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try{
            DB::beginTransaction();
            $region = Region::create([
                'name' => $this->name,
                'location' => $this->location
            ]);
            $this->triggerDualControl(get_class($region), $region->id, DualControl::ADD, 'adding region');
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.region.index');
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e .', '. Auth::user());
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.region.index');
        }
    }

    public function render()
    {
        return view('livewire.region-add-modal');
    }
}
