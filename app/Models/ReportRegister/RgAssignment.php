<?php

namespace App\Models\ReportRegister;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function assignee(){
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assigner(){
        return $this->belongsTo(User::class, 'assigner_id');
    }
}
