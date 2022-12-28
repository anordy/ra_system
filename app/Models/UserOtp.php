<?php

namespace App\Models;

use App\Events\SendMail;
use App\Events\SendSms;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserOtp extends Model
{
    use HasFactory;
    protected $guarded = [];
    const EXPIRATION_TIME = 5;

    public function user()
    {
        return $this->morphTo();
    }

    public static function generate($codeLength = 5){
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

    public function sendCode($code)
    {
        if (config('app.env') == 'production') {
            event(new SendSms('otp', $this->id, ['code' => $code]));
            event(new SendMail('otp', $this->id, ['code' => $code]));
        }
    }
}
