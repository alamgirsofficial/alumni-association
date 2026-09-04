<?php

namespace App\Responses;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Login successful.'),
        ]);

        return redirect()->intended(config('fortify.home'));
    }
}