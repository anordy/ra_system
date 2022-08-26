<?php

namespace App\Models\Debts;

use App\Models\User;
use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SentDemandNotice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function debt(){
        return $this->belongsTo(Debt::class, 'debt_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'sent_by_id');
    }
}
