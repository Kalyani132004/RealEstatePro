<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Admins can do anything — short-circuits every other check below.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAgent();
    }

    public function view(User $user, Property $property): bool
    {
        return $user->isAgent() && $property->agent_id === $user->agent?->id;
    }

    public function create(User $user): bool
    {
        return $user->isAgent();
    }

    public function update(User $user, Property $property): bool
    {
        return $user->isAgent() && $property->agent_id === $user->agent?->id;
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->isAgent() && $property->agent_id === $user->agent?->id;
    }

    /**
     * Managing gallery images / media uploads follows the same rule as update.
     */
    public function manageMedia(User $user, Property $property): bool
    {
        return $this->update($user, $property);
    }
}
