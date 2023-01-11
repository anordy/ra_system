<?php

namespace App\Models;

use App\Services\Verification\PayloadInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ApiUser extends Model implements PayloadInterface, Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $guarded = [];


    public static function getPayloadColumns(): array
    {
        return ['id', 'app_name', 'app_url', 'username'];
    }

    public static function getTableName(): string
    {
        return 'api_users';
    }

    protected $auditExclude = [
        'password',
        'ci_payload',
    ];
}
