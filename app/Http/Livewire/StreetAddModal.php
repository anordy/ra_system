<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Models\Street;
use App\Models\Ward;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StreetAddModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $regions = [];
    public $districts = [];
    public $wards = [];
    public $region_id;
    public $district_id;
    public $ward_id;
    public $name;

    protected $rules = [
        'region_id' => 'required',
        'district_id' => 'required',
        'ward_id' => 'required',
        'name' => 'required|strip_tag',
    ];

    public function mount()
    {
        $this->regions = Region::where('is_approved',1)->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region_id') {
            $this->districts = District::where('region_id', $this->region_id)->where('is_approved',1)->select('id', 'name')->get();
        }

        if ($propertyName === 'district_id'){
            $this->wards = Ward::where('district_id', $this->district_id)->where('is_approved', 1)->select('id', 'name')->get();
        }
    }


    public function submit()
    {
        if (!Gate::allows('setting-street-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $street = Street::create([
                'name' => $this->name,
                'ward_id' => $this->ward_id,
                'created_at' =>Carbon::now()
            ]);
            DB::commit();
            $this->triggerDualControl(get_class($street), $street->id, DualControl::ADD, 'adding street'.$this->name);
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.street.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.street.index');
        }
    }

    public function render()
    {
        return view('livewire.street-add-modal');
    }
}
