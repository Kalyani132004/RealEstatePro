<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been blocked. Please contact support.']);
        }

        $dashboardRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'agent' => 'agent.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->intended(route($dashboardRoute))
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
