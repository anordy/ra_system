<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainRegion extends Model
{
    protected $guarded = [];
    use HasFactory;

    public const UNG = 'UNG';
    public const PMB = 'PMB';
    protected $table = 'main_regions';

    protected $fillable = [
        'name',
        'location'
   ];
}
