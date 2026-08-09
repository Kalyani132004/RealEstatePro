<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected ImageUploadService $images;

    public function __construct(ImageUploadService $images)
    {
        $this->images = $images;
    }

    /**
     * Show profile page.
     */
    public function edit(Request $request): View
    {
        return view('user.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {

            // Delete old avatar if exists
            if ($user->avatar) {
                $this->images->delete($user->avatar);
            }

            // Upload new avatar
            $validated['avatar'] = $this->images->storeAvatar($request->file('avatar'));
        }

        // Reset email verification if email changed
        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Profile updated successfully.');
    }
}