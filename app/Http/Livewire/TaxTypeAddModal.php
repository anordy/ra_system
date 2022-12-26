<?php

namespace App\Http\Livewire;

use App\Models\TaxType;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TaxTypeAddModal extends Component
{

    use LivewireAlert;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|unique:tax_types',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-tax-type-add')) {
            abort(403);
        }

        $this->validate();
        try{
            TaxType::create([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.tax-type-add-modal');
    }
}
