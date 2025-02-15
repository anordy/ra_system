<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvrReorderPlateNumberFee extends Model
{
	protected $table = 'mvr_reorder_plate_number_fees';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'quantity',
		'is_rfid',
		'is_plate_sticker',
		'amount',
		'gfs_code',
		'mvr_reorder_plate_number_id'
	];


    public function reorder_plate_number()
    {
        return $this->belongsTo(MvrReorderPlateNumber::class,'mvr_reorder_plate_number_id');
    }
}
