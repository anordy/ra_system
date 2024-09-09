<?php

namespace App\Models;

use App\Models\Security\UserAnswer;
use App\Services\Verification\PayloadInterface;
use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements PayloadInterface, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $auditExclude = [
        'password',
        'remember_token',
        'ci_payload',
        'auth_attempt',
        'pass_expired_on',
        'is_first_login',
        'auth_attempt'
    ];

    private $maxAttempts = 3;

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getPayloadColumns(): array
    {
        return ['id', 'email', 'phone', 'status'];
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function level()
    {
        return $this->belongsTo(ApprovalLevel::class);
    }

    public function approvalLevel(){
        return $this->belongsTo(UserApprovalLevel::class);
    }

    public function otp()
    {
        return $this->morphOne(UserOtp::class, 'user');
    }

    public function fullname()
    {
        return $this->fname . ' ' . $this->lname;
    }

    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }

    public function is_role(array $roles)
    {
        if (in_array($this->role->id, $roles)) {
            return true;
        }

        return false;
    }

    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'user');
    }

    public function passwordExistInHistory($password)
    {
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

    public function accountLocked()
    {
        if ($this->status == 0) {
            return true;
        }
        return false;
    }

    public function userAnswers(){
        return $this->morphMany(UserAnswer::class, 'user');
    }

    public function getInitialsAttribute()
    {
        return $this->getInitials($this->fullname());
    }
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper($word[0] ?? '');
        }
        return $initials;
    }
}
