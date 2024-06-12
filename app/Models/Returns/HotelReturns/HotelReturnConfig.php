<?php

namespace App\Models\Returns\HotelReturns;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelReturnConfig extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    const LP = 'LP';
    const IP = 'IP';
    const HS = 'HS';
    const RS = 'RS';
    const TOS = 'TOS';
    const OS = 'OS';
    const HSBNB = 'HSBNB';
    const OSBNB = 'OSNB';


    const PURCHASES = [self::LP, self::IP];
    const SALES = [self::HS, self::RS, self::TOS, self::OS, self::HSBNB, self::OSBNB];

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'taxtype_id');
    }
}
