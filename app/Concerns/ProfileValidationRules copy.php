<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),

            'email' => $this->emailRules($userId),

            'phone' => $this->phoneRules($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return [
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * Email is optional because the user can register
     * using a mobile number.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'nullable',
            'string',
            'email',
            'max:255',

            $userId === null
                ? Rule::unique(User::class, 'email')
                : Rule::unique(User::class, 'email')->ignore($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user phone numbers.
     *
     * Phone is optional because the user can register
     * using an email address.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function phoneRules(?int $userId = null): array
    {
        return [
            'nullable',
            'string',
            'max:20',

            $userId === null
                ? Rule::unique(User::class, 'phone')
                : Rule::unique(User::class, 'phone')->ignore($userId),

            'regex:/^(?:\+8801|01)[3-9]\d{8}$/',
        ];
    }
}