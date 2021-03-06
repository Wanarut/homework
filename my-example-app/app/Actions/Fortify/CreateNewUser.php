<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // username รับ A-Z, a-z, 0-9, _ ความยาวไม่เกิน 12 ตัวอักษร และจะต้องไม่ซ้ำกับที่มีอยู่แล้ว
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:12', 'regex:/^[A-Za-z0-9_]+$/', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validate();

        // Password มีการเข้ารหัสแบบ encrypt ทางเดียว ไม่สามารถ decrypt เพื่อเอา  raw password ได้ ในการบันทึกลงฐานข้อมูล
        // Hashing Password
        $user = User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'last_passwords' => [Hash::make($input['password'])],
        ]);
        
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        return $user;
    }
}
