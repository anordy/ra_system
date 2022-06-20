<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SysModule;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleAssignPermissionModal extends Component
{

    use LivewireAlert;
    public $modules;
    public $role;
    public $permissions;
    public $selectedPermissions =[];


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:roles,name,'.$this->role->id.',id',
        ];
    }

    public function submit()
    {
 
        try {
            if (isset($this->selectedPermissions)) {
                $this->role->refreshPermissions($this->selectedPermissions);
            } else {
                $this->role->permissions()->detach();
            }
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $this->role = Role::find($id);
        $this->selectedPermissions = $this->role->permissions->pluck('id');
        $this->modules = SysModule::all();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.role-assign-permission-modal');
    }
}
