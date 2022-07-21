<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function type(){
        return $this->belongsTo(BusinessFileType::class, 'business_file_type_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }
}
