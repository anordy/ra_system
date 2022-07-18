<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ISIC3 extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'isic3s';

    protected $fillable = [
        'code',
        'description',
        'isic2_id',
    ];

    public function isic2(){
        return $this->belongsTo(ISIC2::class,'isic2_id');
    }

    public function isic4(){
        return $this->hasMany(ISIC4::class,'isic3_id');
    }
}
