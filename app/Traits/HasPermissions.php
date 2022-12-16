<?php

namespace App\Traits;

trait HasPermissions
{


    public function hasModuleTo($module)
    {

        if ($module == '' || $module == null) {
            return false;
        }
        if (!$this->role != null) {
            return false;
        }

        if (!$this->role->permissions->contains('module.name', $module->name)) {
            return false;
        }
        return true;
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission);
    }

    public function hasPermissionThroughRole($permission)
    {

        if ($this->role->permissions->contains('id', $permission->id)) {
            return true;
        }
        return false;
    }
}
