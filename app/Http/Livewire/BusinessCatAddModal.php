<?php

namespace App\Http\Livewire;

use App\Models\BusinessCategory;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessCatAddModal extends Component
{

    use LivewireAlert;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|unique:business_categories',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-business-category-add')) {
            abort(403);
        }

        $this->validate();
        try{
            BusinessCategory::create([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.business-cat-add-modal');
    }
}
