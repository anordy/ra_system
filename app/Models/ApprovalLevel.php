<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    use HasFactory;
    protected $table = 'approval_levels';
    protected $guarded = [];
    
    public function user_level()
    {
        return $this->hasOne(UserApprovalLevel::class, 'approval_level_id', 'id');
    }
}
