<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysModule extends Model
{
    use SoftDeletes;

    protected $table = 'sys_modules';
    protected $fillable = [
        'name',
        'code',
        'id'
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'sys_module_id');


    }
}
