<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Investigation\TaxInvestigation;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessInvestigationAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;


    protected function rules()
    {
        return [
            'name' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            TaxInvestigation::create([
                'name' => $this->name,
                'created_by' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            dd($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.investigation.business.add-modal');
    }
}
