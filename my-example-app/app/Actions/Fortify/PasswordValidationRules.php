<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        // Password เป็นรหัสผ่านความยาวไม่ต่ำกว่า 6 ตัวอักษร ต้องไม่เป็นตัวอักษรหรือตัวเลขเรียงกัน
        // Password should not contain sequential characters
        $password = (new Password)->length(6);

        // Password มีการเข้ารหัสแบบ encrypt ทางเดียว ไม่สามารถ decrypt เพื่อเอา  raw password ได้ ในการบันทึกลงฐานข้อมูล
        // Hashing Password

        return ['required', 'string', $password, 'confirmed'];
    }
}
