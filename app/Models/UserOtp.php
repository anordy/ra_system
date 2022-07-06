<?php

namespace App\Models;

use App\Events\SendMail;
use App\Events\SendSms;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserOtp extends Model
{
    use HasFactory;
    protected $guarded = [];
    const EXPIRATION_TIME = 15;

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
        return $this->created_at->diffInMinutes(Carbon::now()) > static::EXPIRATION_TIME;
    }

    public function sendCode()
    {
        if (!$this->user) {
            throw new \Exception("No user attached to this token.");
        }

        if (!$this->code) {
            $this->code = $this->generateCode();
        }


        try {
            event(new SendSms('otp', $this->id));
            event(new SendMail('otp', $this->id));
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
