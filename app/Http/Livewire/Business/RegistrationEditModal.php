<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class RegistrationEditModal extends Component
{
    use LivewireAlert;
    public $name;
    public $tin;


    protected function rules()
    {
        return [
            'name' => 'required|unique:businesses,name,'.$this->business->id.',id',
            'tin' => 'required|min:4|unique:businesses,tin,'.$this->business->id.',id',
        ];
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->user->update([
                'name' => $this->name,
                'tin' => $this->tin,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $data = Business::find($id);
        $this->business = $data;
        $this->name = $data->name;
        $this->tin = $data->tin;
    }

    public function render()
    {
        return view('livewire.business.registration-edit-modal');
    }
}
