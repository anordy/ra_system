<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class StripTag implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public static function handle(): string
    {
        return 'strip_tag';
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

        // Since strip tags always return string,
        // in order to apply this rule on non string values, like ID's,
        // the result should explicitly be converted to string or do a loose comparison ==

        return strip_tags($value) === (string)$value;
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
        return 'The :attribute must not contain tags.';
    }
}
