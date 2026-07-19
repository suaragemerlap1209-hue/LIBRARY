<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
        public function __invoke(Request $request): RedirectResponse|View
        {
            if ($request->user()->hasVerifiedEmail()) {
                $route = $request->user()->role === 'admin'
                    ? 'dashboard'
                    : 'member.dashboard';
                return redirect()->intended(route($route));
            }

            return view('auth.verify-email');
        }

}
