<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmRecon extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function reconTrans(){
        return $this->hasMany(ZmReconTran::class);
    }

    public function reconTransIDs(){
        return $this->reconTrans()->pluck('BillCtrNum');
    }
}
