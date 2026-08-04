<?php

namespace Tests;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates + logs in a plain "user" role account and returns it.
     */
    protected function actingAsUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Creates + logs in an agent (User with role=agent + an Agent profile
     * row) and returns the User model — access the Agent profile via $user->agent.
     */
    protected function actingAsAgent(array $userAttributes = [], array $agentAttributes = []): User
    {
        $user = User::factory()->agent()->create($userAttributes);
        Agent::factory()->create(array_merge(['user_id' => $user->id], $agentAttributes));
        $this->actingAs($user->fresh());

        return $user->fresh(['agent']);
    }

    protected function actingAsAdmin(array $attributes = []): User
    {
        $user = User::factory()->admin()->create($attributes);
        $this->actingAs($user);

        return $user;
    }
}
