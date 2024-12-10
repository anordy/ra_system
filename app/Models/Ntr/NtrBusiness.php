<?php

namespace App\Models\Ntr;

use App\Models\Country;
use App\Models\Taxpayer;
use App\Services\Verification\PayloadInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NtrBusiness extends Authenticatable implements PayloadInterface
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getPayloadColumns(): array
    {
        return [
            'id',
            'email',
        ];
    }

    public static function getTableName(): string
    {
        return 'taxpayers';
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'ntr_taxpayer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function category()
    {
        return $this->belongsTo(NtrBusinessCategory::class, 'ntr_business_category_id');
    }

    public function nature()
    {
        return $this->belongsTo(NtrNatureOfBusiness::class, 'ntr_business_nature_id');
    }

    public function owner()
    {
        return $this->hasOne(NtrBusinessOwner::class, 'ntr_business_id');
    }

    public function gateway()
    {
        return $this->belongsTo(NtrPaymentGateway::class, 'ntr_payment_gateway_id');
    }

    public function attachments()
    {
        return $this->hasMany(NtrBusinessAttachment::class, 'ntr_business_id');
    }

    public function contacts()
    {
        return $this->hasMany(NtrBusinessContactPerson::class, 'ntr_business_id');
    }

    public function socials()
    {
        return $this->hasMany(NtrBusinessSocialAccount::class, 'ntr_business_id');
    }

}
