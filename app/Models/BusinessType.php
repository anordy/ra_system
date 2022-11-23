<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    use HasFactory;

    public const HOTEL = 'hotel';
    public const OTHER = 'other';
    public const ELECTRICITY = 'electricity';

}
