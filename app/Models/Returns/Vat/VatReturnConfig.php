<?php

namespace App\Models\Returns\Vat;

use App\Models\FinancialYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnConfig extends Model
{
    use HasFactory;
    protected $table = 'vat_return_configs';
    protected $guarded = [];

    const PURCHASES = [
        'EIP',
        'ELP',
        'NCP',
        'VDP',
        'SLP',
        'IP',
        'SRI',
        'SA',
        'SC',
    ];
    const SALES = ['SRS', 'ZRS', 'ES', 'SER'];


    public function items()
    {
        return $this->hasMany(VatReturnItem::class, 'config_id', 'id');
    }

    public function year()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }
}
