<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
        

        // $latest = array_pop($user->passwords);
        // array_unshift($user->passwords, Hash::make($input['password']));
        $last_passwords = $user->last_passwords;
        $last_passwords[0] = Hash::make($input['password']);
        $user->last_passwords = $last_passwords;
        $user->save();
    }
}
