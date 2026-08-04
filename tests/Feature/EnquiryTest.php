<?php

namespace Tests\Feature;

use App\Mail\EnquiryNotifyAgentMail;
use App\Mail\EnquiryReceivedMail;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_submit_an_enquiry(): void
    {
        Mail::fake();
        $property = Property::factory()->create();

        $response = $this->postJson(route('enquiries.store'), [
            'property_id' => $property->id,
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'phone' => '9876543210',
            'message' => 'I would like to schedule a visit.',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('enquiries', [
            'property_id' => $property->id,
            'email' => 'buyer@example.com',
            'user_id' => null,
            'agent_id' => $property->agent_id,
            'status' => 'new',
        ]);
    }

    public function test_enquiry_submission_queues_both_notification_emails(): void
    {
        Mail::fake();
        $property = Property::factory()->create();

        $this->postJson(route('enquiries.store'), [
            'property_id' => $property->id,
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'phone' => '9876543210',
            'message' => 'I would like to schedule a visit.',
        ]);

        Mail::assertQueued(EnquiryReceivedMail::class);
        Mail::assertQueued(EnquiryNotifyAgentMail::class);
    }

    public function test_a_logged_in_user_enquiry_is_linked_to_their_account(): void
    {
        Mail::fake();
        $user = $this->actingAsUser();
        $property = Property::factory()->create();

        $this->postJson(route('enquiries.store'), [
            'property_id' => $property->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '9876543210',
            'message' => 'Interested!',
        ]);

        $this->assertDatabaseHas('enquiries', [
            'property_id' => $property->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_enquiry_requires_all_fields(): void
    {
        $property = Property::factory()->create();

        $this->postJson(route('enquiries.store'), [
            'property_id' => $property->id,
        ])->assertJsonValidationErrors(['name', 'email', 'phone', 'message']);
    }

    public function test_enquiry_requires_a_valid_property_id(): void
    {
        $this->postJson(route('enquiries.store'), [
            'property_id' => 999999,
            'name' => 'Someone',
            'email' => 'someone@example.com',
            'phone' => '9876543210',
            'message' => 'Hello',
        ])->assertJsonValidationErrors(['property_id']);
    }
}
