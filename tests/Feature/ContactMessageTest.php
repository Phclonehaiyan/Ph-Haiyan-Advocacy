<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_messages_are_stored(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Ana Reyes',
            'email' => 'ana@example.com',
            'phone' => '+63 917 555 0110',
            'organization' => 'Leyte Green Schools',
            'inquiry_type' => 'partnership',
            'subject' => 'Partnership inquiry for a school forum',
            'message' => 'We would like to explore a resilience forum with our student leaders and would appreciate details on next steps.',
        ]);

        $response
            ->assertRedirect(route('contact.index').'#contact-form')
            ->assertSessionHas('status');

        $this->assertDatabaseCount(ContactMessage::class, 1);
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'ana@example.com',
            'inquiry_type' => 'partnership',
            'status' => 'new',
        ]);
    }
}
