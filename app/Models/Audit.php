<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $guarded = [];
    use HasFactory;

    public const ACTIVATED = 'activated';
    public const DEACTIVATED = 'deactivated';
    public const UPDATED = 'updated';
    public const CREATED = 'created';
    public const DELETED = 'deleted';
    public const RESTORED = 'restored';
    public const LOGGED_IN = 'logged in';
    public const LOGGED_OUT = 'logged out';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const ASSIGNED = 'assigned';


    public function user(){
        return $this->morphTo('user');
    }

    public function auditable(){
        return $this->morphTo('auditable');
    }
}
