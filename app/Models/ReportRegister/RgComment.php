<?php

namespace App\Models\ReportRegister;

use App\Enum\ReportRegister\RgRequestorType;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'commenter_type' => 'integer'
    ];

    public function getCommenterNameAttribute()
    {
        $commenterName = 'N/A';
        if ($this->commenter_type === RgRequestorType::TAXPAYER) {
            $taxpayer = Taxpayer::find($this->commenter_id, ['first_name', 'middle_name', 'last_name']);
            if ($taxpayer) {
                $commenterName = $taxpayer->fullname;
            }
        } else if ($this->commenter_type === RgRequestorType::STAFF) {
            $staff = User::find($this->commenter_id, ['fname', 'lname']);
            if ($staff) {
                $commenterName = $staff->fullname;
            }
        } else {
            return 'N/A';
        }
        return $commenterName;
    }

    public function getCommenterInitialsAttribute()
    {
        return $this->getInitials($this->getCommenterNameAttribute());
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper($word[0] ?? '');
        }
        return $initials;
    }

}
