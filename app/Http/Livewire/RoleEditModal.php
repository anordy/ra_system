<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
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
            $payload = [
                'name' => $this->name,
                'report_to' => $this->report_to == 'null' ? null : $this->report_to
            ];
            $this->role->update();
            $this->triggerDualControl(get_class($this->role), $this->role->id, DualControl::EDIT, 'editing role', json_encode($payload));
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
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
