<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryBusinessClosure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(business::class, 'business_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('opening_date', '<=', date('Y-m-d'));
    }

}
