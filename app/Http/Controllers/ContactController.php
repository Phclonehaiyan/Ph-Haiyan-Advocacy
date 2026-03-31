<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\ContactMessageConfirmation;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $inquiryGuides = $this->inquiryGuides();
        $selectedInquiryType = $request->string('inquiry')->toString();
        $selectedGuide = $inquiryGuides[$selectedInquiryType] ?? null;

        return view('contact.index', [
            'page' => Page::published()->where('slug', 'contact')->firstOrFail(),
            'selectedInquiryType' => array_key_exists($selectedInquiryType, $inquiryGuides) ? $selectedInquiryType : null,
            'selectedSubjectSuggestion' => $selectedGuide['subject'] ?? null,
            'inquiryGuides' => $inquiryGuides,
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $contactMessage = ContactMessage::query()->create([
            ...$request->validated(),
            'status' => 'new',
            'submitted_at' => now(),
        ]);

        try {
            Mail::to(config('site.contact.email'))->send(new ContactMessageReceived($contactMessage));
            Mail::to($contactMessage->email)->send(new ContactMessageConfirmation($contactMessage));
        } catch (\Throwable $exception) {
            Log::error('Contact message email notification failed.', [
                'contact_message_id' => $contactMessage->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()
            ->to(route('contact.index').'#contact-form')
            ->with('status', 'Your inquiry has been sent successfully. The PH Haiyan team has received your message.');
    }

    private function inquiryGuides(): array
    {
        return [
            'volunteer' => [
                'label' => 'Volunteer',
                'description' => 'Join field visits, public forums, planting activities, and community-facing resilience work.',
                'subject' => 'Volunteer interest for PH Haiyan activities',
                'icon' => 'users',
            ],
            'donate-support' => [
                'label' => 'Donate / Support',
                'description' => 'Support tree-growing, climate education, public-interest letters, and resilience campaigns.',
                'subject' => 'Support inquiry for PH Haiyan advocacy work',
                'icon' => 'heart',
            ],
            'partnership' => [
                'label' => 'Partnership',
                'description' => 'Coordinate with PH Haiyan on programs, forums, educational efforts, and institutional collaboration.',
                'subject' => 'Partnership inquiry for PH Haiyan Advocacy Inc.',
                'icon' => 'handshake',
            ],
            'general-inquiry' => [
                'label' => 'General Inquiry',
                'description' => 'Ask a general question, request clarification, or reach out about another concern.',
                'subject' => 'General inquiry for PH Haiyan Advocacy Inc.',
                'icon' => 'mail',
            ],
        ];
    }
}
