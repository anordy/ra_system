<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BFOReturnItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bfo_return_items';
}
