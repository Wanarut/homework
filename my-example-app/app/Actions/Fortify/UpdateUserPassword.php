<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            /* Checking if the current password is the same as the password in the database. */
            if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
            /* Checking if the new password is the same as the last 5 passwords. */
            foreach ($user->last_passwords as $password) {
                if (Hash::check($input['password'], $password)) {
                    $validator->errors()->add('password', __('The provided password matches your last 5 passwords.'));
                }
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        $passwords = $user->last_passwords;
        /* Checking if the user has 5 passwords in the database. */
        if (count($passwords) >= 5) {
            $latest = array_pop($passwords);
        }
        array_unshift($passwords, Hash::make($input['password']));
        $user->last_passwords = $passwords;
        $user->save();
    }
}
