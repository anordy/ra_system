<?php

namespace App\Models\TaxAudit;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAuditOfficer extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'audit_id' => 'integer',
    ];

    protected $guarded = [];

    public function taxAudit()
    {
        return $this->belongsTo(TaxAudit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
