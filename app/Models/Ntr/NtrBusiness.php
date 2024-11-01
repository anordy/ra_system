<?php

namespace App\Models\Ntr;

use App\Models\Country;
use App\Models\MainRegion;
use App\Models\Taxpayer;
use App\Models\TaxRegion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NtrBusiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

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
