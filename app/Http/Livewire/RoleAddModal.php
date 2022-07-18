<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $report_to = null;
    public $roles;

    public function mount()
    {
        $this->roles = Role::all();
    }


    protected function rules()
    {
        return [
            'name' => 'required|unique:roles',
            'report_to' => 'nullable|unique:roles',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            Role::create([
                'name' => $this->name,
                'report_to' => $this->report_to,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.role-add-modal');
    }
}
