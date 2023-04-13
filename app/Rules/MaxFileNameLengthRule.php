<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFileNameLengthRule implements Rule
{
    protected $maxLength;

    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function passes($attribute, $value)
    {
        $fileNameLength = strlen($value->getClientOriginalName());

        return $fileNameLength <= $this->maxLength;
    }

    public function message()
    {
        return 'The :attribute has a file name that is too long. Please change the file name and try again (maximum ' . $this->maxLength . ' characters)';
    }
}
