<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictAddModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

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
        $this->regions = Region::where('is_approved',1)->get();
    }


    public function submit()
    {
        if (!Gate::allows('setting-district-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $district = District::create([
                'name' => $this->name,
                'region_id' => $this->region_id,
                'created_at' =>Carbon::now()
            ]);
            $this->triggerDualControl(get_class($district), $district->id, DualControl::ADD, 'adding new district '.$this->name.'');
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.district.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.district.index');
        }
    }

    public function render()
    {
        return view('livewire.district-add-modal');
    }
}
