<?php

namespace App\Models\PublicService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeRegistration extends Model
{
    use HasFactory;

    protected $table = 'public_service_de_registrations';

    protected $guarded;
}
