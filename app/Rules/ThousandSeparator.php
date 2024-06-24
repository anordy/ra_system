<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class ThousandSeparator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public static function handle(): string
    {
        return 'thousand_separator';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (is_null($value)){
            return true;
        }

        $pattern = '/^[+]?([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?|\d*\.\d+|\d+)$/';

        if (!preg_match($pattern, $value)) {
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
    public function message(): string
    {
        return __('The :attribute must be a valid number');
    }
}
