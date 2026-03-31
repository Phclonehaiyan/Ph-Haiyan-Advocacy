<?php

namespace Tests\Feature;

use App\Mail\ContactMessageConfirmation;
use App\Mail\ContactMessageReceived;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_sends_admin_and_sender_emails(): void
    {
        Mail::fake();

        Page::query()->create([
            'slug' => 'contact',
            'title' => 'Contact',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $payload = [
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'phone' => '+63 900 000 0000',
            'organization' => 'Sample Org',
            'inquiry_type' => 'general-inquiry',
            'subject' => 'Testing the contact form',
            'message' => 'This is a detailed test message with more than eight words so validation passes correctly.',
            'website' => '',
        ];

        $this->post(route('contact.store'), $payload)
            ->assertRedirect(route('contact.index').'#contact-form')
            ->assertSessionHas('status');

        Mail::assertSent(ContactMessageReceived::class);
        Mail::assertSent(ContactMessageConfirmation::class, function (ContactMessageConfirmation $mail) use ($payload) {
            return $mail->contactMessage->email === $payload['email'];
        });

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'sender@example.com',
            'subject' => 'Testing the contact form',
            'status' => 'new',
        ]);
    }
}
