<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    public $timestamps = true;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
    
}
