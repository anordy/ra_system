<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAgentStatus extends Model
{
    use HasFactory;

    public const DRAFTING =  'drafting';
    public const PENDING =   'pending';
    public const APPROVED =  'approved';
    public const REJECTED =  'rejected';
    public const COMPLETED = 'completed';
}
