<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;
use App\Rules\PasswordRules;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        // Password เป็นรหัสผ่านความยาวไม่ต่ำกว่า 6 ตัวอักษร
        $password = (new Password)->length(6);

        return ['required', 'string', $password, 'confirmed', new PasswordRules];
    }
}
