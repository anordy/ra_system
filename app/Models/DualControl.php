<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DualControl extends Model
{
    use HasFactory;
    protected $guarded = '';

    public const ADD = 'add';
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    public const DEACTIVATE = 'deactivate';
    public const ACTIVATE = 'activate';

}
