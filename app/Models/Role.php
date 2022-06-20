<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    protected $fillable = [
         'name'
    ];
    protected $touches = ['permissions'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function givePermissionTo(array $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions == null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('id', $permissions)->get();
    }

    // Getting all permission

    public function getPermissions(array $permissions)
    {
        return Permission::whereIn('name', $permissions)->get();
    }

    public function hasAccess($permission)
    {
        return (bool)$this->permissions()->where('name', $permission)->count();
    }

    public function refreshPermissions(array $permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionTo($permissions);
    }
}
