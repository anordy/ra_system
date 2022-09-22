<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleEditModal extends Component
{

    use LivewireAlert;
    public $name;
    public $report_to = null;
    public $roles;


    protected function rules()
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->role->id . ',id',
            'report_to' => 'nullable|unique:roles',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-role-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->role->update([
                'name' => $this->name,
                'report_to' => $this->report_to == 'null' ? null : $this->report_to
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $data = Role::find($id);
        $this->role = $data;
        $this->name = $data->name;
        $this->report_to = $data->report_to;

        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.role-edit-modal');
    }
}
