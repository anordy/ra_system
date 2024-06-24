<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvrTemporaryTransportFileType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeApproved($query){
        return $query->where('is_approved', true);
    }
}
