<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class NidaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public static function handle(): string
    {
        return 'nida';
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
        // Since strip_tags will return false for null values.
        if (is_null($value)){
            return true;
        }

        // Check for is string must start with 1 or 2 and all characters must be numbe
        $pattern = '/^[1-2]\d{19}$/';

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
        return 'Invalid nida number format or nida number must be 20 characters';
    }
}
