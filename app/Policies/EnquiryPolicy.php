<?php

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;

class EnquiryPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    /**
     * A user may view their own enquiry, or the agent who owns the
     * related property may view it.
     */
    public function view(User $user, Enquiry $enquiry): bool
    {
        if ($user->isUser()) {
            return $enquiry->user_id === $user->id;
        }

        if ($user->isAgent()) {
            return $enquiry->agent_id === $user->agent?->id;
        }

        return false;
    }

    /**
     * Only the owning agent may change an enquiry's status
     * (new -> contacted -> closed).
     */
    public function updateStatus(User $user, Enquiry $enquiry): bool
    {
        return $user->isAgent() && $enquiry->agent_id === $user->agent?->id;
    }
}
