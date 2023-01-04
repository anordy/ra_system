<?php

namespace App\Models;

use App\Models\Returns\Vat\VatReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TaxType extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public const VAT = 'vat';
    public const HOTEL = 'hotel-levy';
    public const RESTAURANT = 'restaurant-levy';
    public const TOUR_OPERATOR = 'tour-operator-levy';
    public const LAND_LEASE = 'land-lease';
    public const PUBLIC_SERVICE = 'public-service';
    public const EXCISE_DUTY_MNO = 'excise-duty-mno';
    public const EXCISE_DUTY_BFO = 'excise-duty-bfo';
    public const PETROLEUM = 'petroleum-levy';
    public const AIRPORT_SERVICE_SAFETY_FEE = 'airport-service-safety-fee';
    public const SEA_SERVICE_TRANSPORT_CHARGE = 'sea-service-transport-charge';
    public const TAX_CONSULTANT = 'tax-consultant';
    public const STAMP_DUTY = 'stamp-duty';
    public const LUMPSUM_PAYMENT = 'lumpsum-payment';
    public const ELECTRONIC_MONEY_TRANSACTION = 'electronic-money-transaction';
    public const MOBILE_MONEY_TRANSFER = 'mobile-money-transfer';
    public const PENALTY = 'penalty';
    public const INTEREST = 'interest';
    public const INFRASTRUCTURE = 'infrastructure';
    public const RDF = 'road-development-fund';
    public const ROAD_LICENSE_FEE = 'road-license-fee';
    public const AUDIT = 'audit';
    public const VERIFICATION = 'verification';
    public const DISPUTES = 'disputes';
    public const WAIVER = 'waiver';
    public const OBJECTION = 'objection';
    public const WAIVER_OBJECTION = 'waiver-and-objection';
    public const INVESTIGATION = 'investigation';
    public const GOVERNMENT_FEE = 'government-fee';
    public const DEBTS = 'debts';
    public const AIRBNB = 'hotel-airbnb';

    protected $fillable = [
        'name', 'gfs_code', 'is_approved', 'is_updated'
    ];

    public function landLeases()
    {
        return $this->hasMany(LandLease::class, 'taxpayer_id');
    }

    public function vatReturn()
    {
        return $this->hasOne(VatReturn::class, 'taxtype_id', 'id');
    }

    public function scopeMain($query){
        $query->where('category', 'main');
    }
}
