<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class ArrayNumberRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public static function handle(): string
    {
        return 'arr_num';
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

        if (is_array($value) || is_numeric($value)) {
            return true;
        } else {
            return false;
        }
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
        return 'The :attribute must contain be either array or number';
    }
}
