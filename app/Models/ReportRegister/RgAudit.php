<?php

namespace App\Models\ReportRegister;

use App\Enum\ReportRegister\RgRequestorType;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgAudit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
      'actor_type' => 'integer'
    ];

    public function getActorNameAttribute()
    {
        $actorName = 'N/A';
        if ($this->actor_type === RgRequestorType::TAXPAYER) {
            $taxpayer = Taxpayer::find($this->actor_id, ['first_name', 'middle_name', 'last_name']);
            if ($taxpayer) {
                $actorName = $taxpayer->fullname;
            }
        } else if ($this->actor_type === RgRequestorType::STAFF) {
            $staff = User::find($this->actor_id, ['fname', 'lname']);
            if ($staff) {
                $actorName = $staff->fullname;
            }
        } else {
            return 'N/A';
        }
        return $actorName;
    }

}
