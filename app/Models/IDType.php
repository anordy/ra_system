<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IDType extends Model
{
    use HasFactory, SoftDeletes;

    public const NIDA = 'NIDA';
    public const ZANID = 'ZANID';
    public const NIDA_ZANID = 'NIDA & ZANID';
    public const PASSPORT = 'PASSPORT';
    public const TIN = 'TIN';

    protected $table = 'id_types';

    protected $fillable = [
        'name',
        'description'
    ];
}
