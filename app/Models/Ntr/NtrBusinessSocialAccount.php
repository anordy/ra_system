<?php

namespace App\Models\Ntr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusinessSocialAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(NtrSocialAccount::class, 'ntr_social_account_id');
    }
}
