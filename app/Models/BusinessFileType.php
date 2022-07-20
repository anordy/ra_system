<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessFileType extends Model
{
    use HasFactory, SoftDeletes;

    public const TIN = 'tin';
    public const TITLE_DEED = 'title_deed';
    public const BUSINESS_LICENSE = 'business_license';
    public const ID_CARD = 'id_card';

    protected $guarded = [];

    public function file($businessId){
        return $this->hasOne(BusinessFile::class)->where('business_id', $businessId)->first();
    }

    public function tin($businessId, $taxpayerId, $fileTypeId){
        return $this->hasOne(BusinessFile::class)
            ->where('business_id', $businessId)
            ->where('taxpayer_id', $taxpayerId)
            ->where('business_file_type_id', $fileTypeId)
            ->first();
    }
}
