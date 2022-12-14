<?php

namespace App\Models;

use App\Events\SendMail;
use App\Events\SendSms;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $guarded = [];
    const EXPIRATION_TIME = 5;

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }

        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->morphTo();
    }


    public function generateCode($codeLength = 5)
    {
        $min = pow(10, $codeLength);
        $max = $min * 10 - 1;
        $code = mt_rand($min, $max);

        if (config('app.env') == 'local') {
            return '123456';
        }

        return $code;
    }

    public function isValid()
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    public function isUsed()
    {
        return $this->used;
    }

    public function isExpired()
    {
        return $this->updated_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }

    public function sendCode()
    {
        if (config('app.env') == 'production') {
            event(new SendSms('otp', $this->id));
            event(new SendMail('otp', $this->id));
        }
    }
}
