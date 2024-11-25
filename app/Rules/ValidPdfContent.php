<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;
use Symfony\Component\Mime\MimeTypes;

class ValidPdfContent implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public static function handle(): string
    {
        return 'valid_pdf';
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
	return true;

        if (!is_file($value->path())) {
            return false;
        }

        // Check file content using symfony/mime library
        $mimeType = (new MimeTypes())->guessMimeType($value->path());

        if ($mimeType != 'application/pdf') {
            return false;
        }

        $pdfContent = file_get_contents($value->path());

        // Since pdf can be various versions eg. PDF-1.5,1.0 etc.
        if (preg_match("/^%PDF-/", $pdfContent)) {
            return true;
        }

        return false;
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
        return __('The :attribute must be a valid pdf');
    }
}
