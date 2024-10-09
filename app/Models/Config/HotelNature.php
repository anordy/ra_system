<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelNature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const NORMAL = 'Normal';
    public const SMALL_ISLAND = 'Small Island';
    public const UNDER_THE_OCEAN = 'Under the Ocean';
}
