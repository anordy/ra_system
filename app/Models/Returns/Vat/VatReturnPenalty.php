<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturnPenalty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vat_return_penalties';
    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(VatReturn::class);
    }
}
