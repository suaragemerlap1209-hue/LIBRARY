<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
        public function __invoke(EmailVerificationRequest $request): RedirectResponse
        {
            // Debug sementara — hapus setelah fix
            \Log::info('Verify attempt', [
                'user' => $request->user()->email,
                'hasVerified' => $request->user()->hasVerifiedEmail(),
                'marked' => $request->user()->markEmailAsVerified(),
            ]);

            if ($request->user()->hasVerifiedEmail()) {
                $route = $request->user()->role === 'admin' ? 'dashboard' : 'member.dashboard';
                return redirect()->intended(route($route));
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            $route = $request->user()->role === 'admin' ? 'dashboard' : 'member.dashboard';
            return redirect()->intended(route($route));
        }
}
