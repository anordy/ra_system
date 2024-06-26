<?php

namespace App\Models\PublicService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicServicePaymentInterval extends Model
{
    use HasFactory;

    protected $table = 'public_service_payments_interval';

    protected $guarded = [];

    public const ANNUAL = 12;
}
