<?php

namespace App\Models;

use App\Models\VatReturn\VatReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TaxType extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public const VAT               = 'vat';
    public const HOTEL             = 'hotel-levy';
    public const RESTAURANT        = 'restaurant-levy';
    public const TOUR              = 'tour-operator-levy';
    public const LAND              = 'land-lease';
    public const PUBLIC_SERVICE    = 'public-service';
    public const EXCISE_DUTY       = 'excise-duty';
    public const PETROLEUM         = 'petroleum-levy';
    public const AIRPORT_SERVICE   = 'airport-service';
    public const AIRPORT_SAFETY    = 'airport-safety';
    public const SEAPORT_SERVICE   = 'seaport-service';
    public const SEAPORT_TRANSPORT = 'seaport-transport';
    public const TAX_CONSULTANT    = 'tax-consultant';
    public const STAMP_DUTY        = 'stamp-duty';
    public const LUMPSUM_PAYMENT   = 'lumpsum-payment';
    public const ELECTRONIC_MONEY_TRANSACTION   = 'electronic-money-transaction';
    public const MOBILE_MONEY_TRANSFER   = 'mobile-money-transfer';

    protected $fillable = [
        'name',
    ];

    public function vatReturn()
    {
        return $this->hasOne(VatReturn::class, 'taxtype_code', 'code');
    }
}
