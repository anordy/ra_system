<?php

namespace App\Http\Livewire;

use App\Models\BusinessCategory;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessCatEditModal extends Component
{

    use LivewireAlert;
    public $name;
    public $businessCategory;

    protected function rules()
    {
        return [
            'name' => 'required|unique:business_categories,name,'.$this->businessCategory->id.',id',
        ];
    }

    public function mount($id)
    {
        $data = BusinessCategory::find($id);
        $this->businessCategory = $data;
        $this->name = $data->name;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->businessCategory->update([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.business-cat-edit-modal');
    }
}
