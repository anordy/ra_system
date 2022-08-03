<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiver extends Model
{
    use HasFactory;

    protected $guarded = [];
    
        public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

}
