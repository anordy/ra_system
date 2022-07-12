<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

	public const PENDING = 'pending';
	public const PAID = 'paid';
	public const PARTIALLY = 'partially';
	public const FAILED = 'failed';
	public const CANCELLED = 'cancelled';

}
