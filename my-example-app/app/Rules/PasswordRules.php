<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRules implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // ต้องไม่เป็นตัวอักษรหรือตัวเลขเรียงกัน
        // Password should not contain sequential characters
        $patterns = [
            '0123456789012345789',
            'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz',
        ];

        foreach ($patterns as $pattern) {
            $pos = strpos($pattern, $value);
            if ($pos === false) {
                continue;
            } else {
                return false;
            }
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
        return 'The password must not be the sequential characters.';
    }
}
