<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use Exception;
use App\Models\Role;
use Livewire\Component;
use App\Models\SysModule;
use App\Models\Permission;
use App\Traits\AuditTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RoleAssignPermissionModal extends Component
{

    use LivewireAlert, AuditTrait;
    public $modules;
    public $role;
    public $permissions;
    public $selectedPermissions =[];
    public $permission_id;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:roles,name,'.$this->role->id.',id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-role-assign-permission')) {
            abort(403);
        }

        try {
            if (isset($this->selectedPermissions)) {
                $this->role->refreshPermissions($this->selectedPermissions);
                $new_permissions = $this->permissions->whereIn('id', $this->selectedPermissions)->pluck('name');
                $this->triggerAudit(Role::class, Audit::UPDATED, 'roles', $this->role->id, $this->role->permissions->pluck('name'), $new_permissions);
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
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
        $this->modules = SysModule::all();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.role-assign-permission-modal');
    }
}
