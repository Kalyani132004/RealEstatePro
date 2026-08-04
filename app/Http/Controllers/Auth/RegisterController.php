<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user->isAgent()) {
            Agent::create([
                'user_id' => $user->id,
                'agency_name' => $validated['agency_name'] ?? null,
                'is_verified' => false,
            ]);
        }

        // event(new Registered($user));

        // Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);

        return redirect()
            ->route($user->isAgent() ? 'agent.dashboard' : 'user.dashboard')
            ->with('success', 'Welcome to RealEstatePro! Please verify your email address.');
    }
}
