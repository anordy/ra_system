<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TemporaryBusinessClosure extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('opening_date', '<=', date('Y-m-d'));
    }

}
