<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;

    public const PDF = 'pdf';
    public const IMAGE = 'image';

    protected $guarded = [];


}
