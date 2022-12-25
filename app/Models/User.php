<?php

namespace App\Models;

use App\Services\Verification\PayloadInterface;
use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements PayloadInterface
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions;
    protected $guarded = [];
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function otp(){
        return $this->morphOne(UserOtp::class, 'user');
    }

    public function fullname(){
        return $this->fname . ' '. $this->lname;
    }

    public function getFullNameAttribute(){
        return "{$this->fname} {$this->lname}";
    }

    public function is_role(array $roles)
    {
        if (in_array($this->role->id, $roles))
        {
            return true;
        }

        return false;
    }

    public static function getPayloadColumns(): array
    {
        return ['id', 'email', 'phone', 'password', 'status'];
    }

    public static function getTableName(): string
    {
        return 'users';
    }
}
