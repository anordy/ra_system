<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SysModule;
use App\Traits\AuditTrait;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RoleAssignPermissionModal extends Component
{

    use CustomAlert, AuditTrait;

    public $modules;
    public $role;
    public $permissions;
    public $selectedPermissions = [];
    public $permission_id;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:roles,name,' . $this->role->id . ',id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-role-assign-permission')) {
            abort(403);
        }

        try {
            if (isset($this->selectedPermissions)) {
                $old_permission = $this->role->permissions->pluck('name');
                $this->role->refreshPermissions($this->selectedPermissions);
                $new_permissions = $this->permissions->whereIn('id', $this->selectedPermissions)->pluck('name');
                $this->triggerAudit(Role::class, Audit::UPDATED, 'roles', $this->role->id, $old_permission, $new_permissions);
            } else {
                $this->role->permissions()->detach();
            }

            // Re-load session permissions
            $role_permissions = DB::table('roles_permissions')->where('role_id', $this->role->id)->pluck('permission_id');
            $permissions = Permission::query()->whereIn('id', $role_permissions)->get();
            $modules = SysModule::query()->whereHas('permissions', function ($query) use ($role_permissions) {
                $query->whereIn('id', $role_permissions);
            })->distinct()->get();

            // Store permissions in session
            Session::put('user_permissions', $permissions);
            Session::put('user_modules', $modules);

            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->role = Role::find($id);
        if (is_null($this->role)) {
            abort(404);
        }
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
        $this->modules = SysModule::all();
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.role-assign-permission-modal');
    }
}
