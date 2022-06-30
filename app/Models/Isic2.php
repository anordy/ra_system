<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ISIC2 extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'isic2s';

    protected $fillable = [
        'code',
        'description',
        'isic1_id',
    ];

    public function isic1(){
        return $this->belongsTo(ISIC1::class,'isic1_id');
    }

    public function isic3(){
        return $this->hasMany(ISIC3::class,'isic2_id');
    }
}
