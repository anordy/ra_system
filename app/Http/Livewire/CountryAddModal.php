<?php

namespace App\Http\Livewire;

use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CountryAddModal extends Component
{

    use LivewireAlert;

    public $code;
    public $name;
    public $nationality;


    protected function rules()
    {
        return [
            'code' => 'required|min:2|unique:countries',
            'name' => 'required|min:2|unique:countries',
            'nationality' => 'required|min:2|unique:countries',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-country-add')) {
            abort(403);
        }

        $this->validate();
        try{
            Country::create([
                'code' => $this->code,
                'name' => $this->name,
                'nationality' => $this->nationality,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.country-add-modal');
    }
}
