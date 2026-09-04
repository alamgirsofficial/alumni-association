<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if ($user && $user->status !== 'approved') {

            // Alumni information এখনো submit করেনি
            if (! $user->alumniInfo) {
                return redirect()->route(
                    'alumni-information.create'
                );
            }

            // Alumni information submit করেছে,
            // কিন্তু admin এখনো approve করেনি
            return redirect()->route(
                'account-status'
            );
        }

        return $next($request);
    }
}