<?php

namespace App\Http\Livewire;

use App\Models\Country;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CountryEditModal extends Component
{

    use LivewireAlert;
    public $code;
    public $name;
    public $nationality;
    public $country;


    protected function rules()
    {
        return [
            'code' => 'required|min:2|unique:countries,code,'.$this->country->id.',id',
            'name' => 'required|min:2|unique:countries,name,'.$this->country->id.',id',
            'nationality' => 'required|min:2|unique:countries,nationality,'.$this->country->id.',id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-country-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->country->update([
                'code' => $this->code,
                'name' => $this->name,
                'nationality' => $this->nationality,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function mount($id)
    {
        $data = Country::find($id);
        $this->country = $data;
        $this->code = $data->code;
        $this->name = $data->name;
        $this->nationality = $data->nationality;
    }

    public function render()
    {
        return view('livewire.country-edit-modal');
    }
}
