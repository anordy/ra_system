<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotInArray implements Rule
{

    protected $array;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !in_array($value, $this->array);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute value is already selected.';
    }
}
