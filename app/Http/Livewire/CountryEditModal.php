<?php

namespace App\Http\Livewire;

use App\Models\Country;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CountryEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;
    public $code;
    public $name;
    public $nationality;
    public $country;
    public $old_values;


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|min:2|unique:countries,code,'.$this->country->id.',id',
            'name' => 'required|strip_tag|min:2|unique:countries,name,'.$this->country->id.',id',
            'nationality' => 'required|strip_tag|min:2|unique:countries,nationality,'.$this->country->id.',id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-country-edit')) {
            abort(403);
        }

        $this->validate();
        if ($this->country->is_approved == DualControl::NOT_APPROVED) {
            $this->alert('error', 'The updated module has not been approved already');
            return;
        }
        try {
            $payload  = [
                'code' => $this->code,
                'name' => $this->name,
                'nationality' => $this->nationality,
            ];
            $this->triggerDualControl(get_class($this->country), $this->country->id, DualControl::EDIT, 'editing country', json_encode($this->old_values), json_encode($payload));
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.country.index');
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.country.index');
        }
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->country = Country::find($id);
        if(is_null($this->country)){
            abort(404);
        }
        $this->code = $this->country->code;
        $this->name = $this->country->name;
        $this->nationality = $this->country->nationality;
        $this->old_values = [
            'code' => $this->code,
            'name' => $this->name,
            'nationality' => $this->nationality,
        ];
    }

    public function render()
    {
        return view('livewire.country-edit-modal');
    }
}
