<?php

namespace App\Models\VatReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatService extends Model
{
    use HasFactory;
	protected $table = 'vat_services';
	protected $guarded = [];
}
