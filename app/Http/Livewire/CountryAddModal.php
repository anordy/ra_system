<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class CountryAddModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $code;
    public $name;
    public $nationality;


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|min:2|unique:countries',
            'name' => 'required|strip_tag|min:2|unique:countries',
            'nationality' => 'required|strip_tag|min:2|unique:countries',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-country-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try{
            $country = Country::create([
                'code' => $this->code,
                'name' => $this->name,
                'nationality' => $this->nationality,
                'created_at' =>Carbon::now()
            ]);
            $this->triggerDualControl(get_class($country), $country->id, DualControl::ADD, 'adding country');
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.country.index');
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('success', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.country.index');
        }
    }

    public function render()
    {
        return view('livewire.country-add-modal');
    }
}
