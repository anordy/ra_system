<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCategory extends Model
{
    use HasFactory, SoftDeletes;

    public const SOLE = 'sole-proprietor';
    public const COMPANY = 'company';
    public const PARTNERSHIP = 'partnership';
    public const NGO = 'ngo';
    public const HOTEL = 'hotel';

    protected $fillable = ['short_name', 'name'];
}
