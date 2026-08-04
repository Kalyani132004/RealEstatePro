<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        $user = $request->user();

        $dashboardRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'agent' => 'agent.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->route($dashboardRoute)->with('success', 'Email verified successfully!');
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        // $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
