<?php

namespace App\Rules;

use App\Inspections\SpamManger;
use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes ($attribute, $value)
    {
        try {
            return ! resolve(SpamManger::class)->detect($value);
        } catch ( \Exception $e ) {
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message ()
    {
        return 'The :attribute Contains Spam.';
    }
}
