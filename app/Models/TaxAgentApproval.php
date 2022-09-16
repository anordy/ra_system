<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAgentApproval extends Model
{
    use HasFactory;
    protected $table = 'taxagent_approvals';
    protected $guarded = [];

    public function tax_agent()
    {
        return $this->belongsTo(TaxAgent::class);
    }
}
