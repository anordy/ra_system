<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ISIC1 extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'isic1s';

    protected $fillable = [
        'code',
        'description',
    ];

    protected $guarded = ['id'];

    public function isic2(){
        return $this->hasMany(ISIC2::class,'isic1_id');
    }

}
