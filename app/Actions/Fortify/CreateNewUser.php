<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, mixed> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            /*
            |--------------------------------------------------------------------------
            | Name
            |--------------------------------------------------------------------------
            */

            'name' => $this->nameRules(),

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            |
            | Email is optional individually.
            | Email OR phone is required.
            |
            */

            'email' => [
                ...$this->emailRules(),
                'required_without:phone',
            ],

            /*
            |--------------------------------------------------------------------------
            | Phone
            |--------------------------------------------------------------------------
            |
            | Phone is optional individually.
            | Email OR phone is required.
            |
            */

            'phone' => [
                ...$this->phoneRules(),
                'required_without:email',
            ],

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            'password' => $this->passwordRules(),

            /*
            |--------------------------------------------------------------------------
            | Terms
            |--------------------------------------------------------------------------
            */

            // 'terms' => [
            //     'accepted',
            // ],

        ], [
            'email.required_without' =>
                'Please provide either an email address or mobile number.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email address is already registered.',

            'phone.required_without' =>
                'Please provide either an email address or mobile number.',

            'phone.unique' =>
                'This mobile number is already registered.',

            'phone.regex' =>
                'Please enter a valid Bangladesh mobile number.',

            'terms.accepted' =>
                'You must accept the registration terms.',
        ])->validate();

        return User::create([
            'name' => $input['name'],

            'email' => $input['email'] ?: null,

            'phone' => $input['phone'] ?: null,

            'password' => $input['password'],
            
            'status' => 'pending',
        ]);
    }
}