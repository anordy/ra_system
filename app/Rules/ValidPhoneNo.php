<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use \libphonenumber\PhoneNumberUtil;

class ValidPhoneNo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public static function name(): string
    {
        return "phone_no";
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($value, "TZ");
            if (!$phoneUtil->isValidNumber($numberProto)) {
                return false;
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field should be a valid phone number format.';
    }
}
