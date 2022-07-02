<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowTask extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function pinstance()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo();
    }
}
