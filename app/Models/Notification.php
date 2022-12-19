<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $guarded = ['id'];

    protected $primaryKey = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }
    
}
