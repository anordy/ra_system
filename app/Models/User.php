<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    public function level()
    {
        return $this->belongsTo(ApprovalLevel::class);
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

    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'user');
    }

    public function passwordExistInHistory($password){
        if (!$this->passwordHistories->isEmpty()) {
            foreach ($this->passwordHistories as $passwordHistory) {
                if (password_verify($password, $passwordHistory->password_entry)) {
                    return $this->passwordHistories;
                    return true;
                }
            }
        }
        return false;
    }
}
