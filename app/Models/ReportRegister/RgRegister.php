<?php

namespace App\Models\ReportRegister;

use App\Enum\ReportRegister\RgRequestorType;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'requester_type' => 'integer',
        'register_type' => 'integer',
        'start_date' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(RgCategory::class, 'rg_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(RgSubCategory::class, 'rg_sub_category_id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function attachments()
    {
        return $this->hasMany(RgAttachment::class, 'rg_register_id');
    }

    public function schedules()
    {
        return $this->hasMany(RgSchedule::class, 'rg_register_id');
    }

    public function schedule()
    {
        return $this->hasMany(RgSchedule::class, 'rg_register_id')->latest();
    }

    public function comments()
    {
        return $this->hasMany(RgComment::class, 'rg_register_id')->latest();
    }

    public function audits()
    {
        return $this->hasMany(RgAudit::class, 'rg_register_id')->latest();
    }

    public function assignees()
    {
        return $this->hasMany(RgAssignment::class, 'rg_register_id')->latest();
    }

    public function currentAssigned()
    {
        return $this->hasOne(RgAssignment::class, 'rg_register_id')->latest();
    }

    public function getRequesterNameAttribute()
    {
        $requesterName = 'N/A';
        if ($this->requester_type === RgRequestorType::TAXPAYER) {
            $taxpayer = Taxpayer::find($this->requester_id, ['first_name', 'middle_name', 'last_name', 'mobile']);
            if ($taxpayer) {
                $requesterName = $taxpayer->fullname;
            }
        } else if ($this->requester_type === RgRequestorType::STAFF) {
            $staff = User::find($this->requester_id, ['fname', 'lname', 'phone']);
            if ($staff) {
                $requesterName = $staff->fullname;
            }
        } else {
            return 'N/A';
        }
        return $requesterName;
    }
}
