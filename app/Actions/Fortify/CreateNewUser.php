<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required','string','max:255'],
            // 'phone' => ['required', 'string', 'size:11', 'regex:/0[7-9][01]\d{8}$/i', Rule::unique(User::class, 'phone')],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email'),
            ],
            'password' => $this->passwordRules(),
            'terms' => ['required', 'boolean', 'in:1'],
        ], [
            'terms.required' => 'You need to tick this box to proceed',
            'terms.in' => 'You need to tick this box to proceed',
            'password.confirmed' => 'Passwords do not match',
            'email.unique' => 'This email address is already registered',
            // 'phone.unique' => 'This phone number is already registered',
            // 'phone.regex' => 'Please input a valid phone number'
        ])->validate();

        // $phoneNumber = formatPhoneNumber($input['phone']);

        $user = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            // 'phone' => $phoneNumber,
            'password' => Hash::make($input['password']),
            'email' => $input['email'],
            'status_id' => status_pending_id(),
            'role_id' => role_user()
        ]);

        if (!$user->wallet) {
            $user->wallet()->create([
                'acc_no' => generateAccountNumber(),
                'is_default' => true,
                'status_id' => status_inactive_id()
            ]);
        }
        $user->sendEmailVerificationNotification();
        return $user;
    }
}
