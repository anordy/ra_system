<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{
    use HasFactory;

    protected $fillable = [
        'cor',
        'make',
        'model',
        'year',
        'chassis_number',
        'inspected_mileage',
        'capacity',
        'engine',
        'body_type',
        'inspected_date',
        'color'
    ];

    public function registration(){
        return $this->belongsTo(MvrRegistration::class);
    }
}
