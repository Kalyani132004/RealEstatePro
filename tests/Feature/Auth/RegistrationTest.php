<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_renders(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Create your account');
    }

    public function test_a_buyer_can_register(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name' => 'Jane Buyer',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'user',
        ]);

        Mail::assertQueued(WelcomeMail::class);
    }

    public function test_an_agent_registration_also_creates_an_agent_profile(): void
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'Alex Agent',
            'email' => 'alex@example.com',
            'phone' => '9876500000',
            'role' => 'agent',
            'agency_name' => 'Skyline Realty',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect(route('agent.dashboard'));

        $user = User::where('email', 'alex@example.com')->firstOrFail();

        $this->assertNotNull($user->agent);
        $this->assertSame('Skyline Realty', $user->agent->agency_name);
        $this->assertFalse((bool) $user->agent->is_verified);
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $this->post(route('register'), [
            'name' => 'Jane Buyer',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'does-not-match',
            'terms' => '1',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_registration_requires_accepting_terms(): void
    {
        $this->post(route('register'), [
            'name' => 'Jane Buyer',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // 'terms' intentionally omitted
        ])->assertSessionHasErrors('terms');
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post(route('register'), [
            'name' => 'Someone Else',
            'email' => 'taken@example.com',
            'phone' => '9876543210',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertSessionHasErrors('email');
    }
}
