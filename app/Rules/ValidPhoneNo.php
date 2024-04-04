<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
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

    public static function handle(): string
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

    public function validate(string $attribute, $value, $params, Validator $validator): bool
    {
        $handle = $this->handle();

        $validator->setCustomMessages([
            $handle => $this->message(),
        ]);

        return $this->passes($attribute, $value);
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
