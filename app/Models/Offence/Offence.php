<?php

namespace App\Models\Offence;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offence extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const PAID = 'paid';
    const CONTROL_NUMBER = 'control-number-generated';

    protected $fillable = [
        'name',
        'amount',
        'mobile',
        'currency',
        'tax_type',
        'status'
    ];

    public function taxTypes()
    {
        return $this->belongsTo(TaxType::class, 'tax_type');
    }
}
