<?php

namespace App\Models\Debts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandNotice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function debt()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class, 'sent_by_id');
    }
}
