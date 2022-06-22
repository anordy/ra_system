<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISIC4 extends Model
{
    use HasFactory;

    protected $table = 'isic4s';

    protected $fillable = [
        'code',
        'description',
        'isic3_id',
    ];

    public function isic3(){
        return $this->belongsTo(ISIC3::class,'isic3_id');
    }
}
