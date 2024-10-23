<?php

namespace App\Models\Ntr;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxpayer() {
        return $this->belongsTo(NtrTaxpayer::class, 'ntr_taxpayer_id');
    }

    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function category() {
        return $this->belongsTo(NtrBusinessCategory::class, 'ntr_business_category_id');
    }

    public function attachments() {
        return $this->hasMany(NtrBusinessAttachment::class, 'ntr_business_id');
    }

    public function contacts() {
        return $this->hasMany(NtrTaxpayer::class, 'ntr_business_id');
    }

    public function socials() {
        return $this->hasMany(NtrBusinessSocialAccount::class, 'ntr_business_id');
    }
}
