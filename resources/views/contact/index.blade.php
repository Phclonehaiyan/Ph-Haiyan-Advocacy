@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    @php
        $contact = config('site.contact');
        $socials = config('site.socials');
        $prefilledInquiryType = old('inquiry_type', $selectedInquiryType ?? null);
        $prefilledSubject = old('subject', $selectedSubjectSuggestion ?? null);
        $mapLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($contact['address']);
        $socialIcons = [
            'Facebook' => 'facebook',
            'YouTube' => 'youtube',
            'Instagram' => 'instagram',
        ];
    @endphp

    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section
        class="section-shell py-12 lg:py-16"
        id="contact-form"
        x-data="{
            inquiry: @js($prefilledInquiryType ?? ''),
            subject: @js($prefilledSubject ?? ''),
            messageText: @js(old('message', '')),
            messageTouched: @js($errors->has('message')),
            sending: false,
            successNotice: @js(session('status')),
            guides: @js($inquiryGuides),
            init() {
                if (this.successNotice) {
                    setTimeout(() => { this.successNotice = null; }, 5000);
                }
            },
            countWords(value) {
                const trimmed = value.trim();
                return trimmed ? trimmed.split(/\s+/).filter(Boolean).length : 0;
            },
            messageReady() {
                return this.messageText.trim().length >= 60 && this.countWords(this.messageText) >= 8;
            },
            readyToSubmit() {
                return this.messageReady();
            },
            choose(type) {
                this.inquiry = type;
                if (!this.subject.trim() && this.guides[type]) {
                    this.subject = this.guides[type].subject;
                }
                this.$nextTick(() => this.$refs.messageField?.focus());
            },
            submitForm(event) {
                this.messageTouched = true;

                if (!this.messageReady()) {
                    event.preventDefault();
                    this.$nextTick(() => this.$refs.messageField?.focus());
                    return;
                }

                this.sending = true;
            }
        }"
    >
        <div
            x-cloak
            x-show="successNotice"
            x-transition.opacity.duration.300ms
            class="fixed right-4 top-24 z-50 w-full max-w-sm rounded-[28px] border border-teal-100 bg-white/95 p-5 shadow-[0_24px_60px_rgba(15,61,46,0.16)] backdrop-blur sm:right-6"
        >
            <div class="flex items-start gap-4">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-teal-100 text-teal-700">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-700">Message sent successfully</div>
                    <p class="mt-2 text-sm leading-7 text-slate-600" x-text="successNotice"></p>
                </div>
                <button
                    type="button"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    @click="successNotice = null"
                    aria-label="Dismiss notification"
                >
                    <x-icon name="close" class="h-4 w-4" />
                </button>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-8 rounded-[30px] border border-teal-100 bg-[linear-gradient(135deg,rgba(240,253,250,0.96),rgba(255,255,255,0.95))] px-6 py-5 shadow-soft">
                <div class="flex items-start gap-4">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-teal-100 text-teal-700">
                        <x-icon name="mail" class="h-5 w-5" />
                    </span>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-700">Message Received</div>
                        <p class="mt-2 text-sm leading-7 text-teal-900">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 rounded-[30px] border border-rose-100 bg-rose-50/90 px-6 py-5 shadow-soft">
                <div class="text-sm font-semibold uppercase tracking-[0.22em] text-rose-700">Please check the form</div>
                <p class="mt-2 text-sm leading-7 text-rose-900">Some fields still need attention before your message can be sent.</p>
            </div>
        @endif

        <div class="grid items-start gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="surface-card self-start">
                <x-section-header
                    eyebrow="Inquiry Form"
                    title="Send a message to the team."
                    description="Choose the inquiry type that best fits your purpose, then leave the details the team needs to respond clearly."
                />

                <form action="{{ route('contact.store') }}" method="POST" class="mt-8 space-y-5" @submit="submitForm">
                    @csrf
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="form-label">Full name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-input" required>
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input" required>
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="form-input" placeholder="+63">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="organization" class="form-label">Organization</label>
                            <input id="organization" name="organization" type="text" value="{{ old('organization') }}" class="form-input">
                            @error('organization') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="inquiry_type" class="form-label">Inquiry type</label>
                            <select id="inquiry_type" name="inquiry_type" class="form-input" x-model="inquiry" required>
                                <option value="">Select an option</option>
                                @foreach ($inquiryGuides as $value => $guide)
                                    <option value="{{ $value }}">{{ $guide['label'] }}</option>
                                @endforeach
                            </select>
                            @error('inquiry_type') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="subject" class="form-label">Subject</label>
                            <input id="subject" name="subject" type="text" class="form-input" x-model="subject" required>
                            @error('subject') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="form-label">Message</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            class="form-input min-h-40"
                            x-ref="messageField"
                            x-model="messageText"
                            @blur="messageTouched = true"
                            @input="messageTouched = true"
                            minlength="60"
                            required
                        >{{ old('message') }}</textarea>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-3 text-xs uppercase tracking-[0.18em] text-slate-400">
                            <span>Include enough detail for the team to understand the request, timeline, and purpose.</span>
                            <span :class="messageReady() ? 'text-teal-700' : 'text-slate-400'">
                                <span x-text="countWords(messageText)"></span> words added
                            </span>
                        </div>
                        <p x-cloak x-show="messageTouched && !messageReady()" class="form-error">
                            Please add at least 8 words with enough detail before sending.
                        </p>
                        @error('message') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-[26px] border border-slate-100 bg-slate-50/80 px-5 py-4">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-pine-700">What happens next</div>
                            <p class="mt-2 text-sm leading-7 text-slate-600">Your message is saved to the site inbox immediately, and the team can follow up through the contact details you provide.</p>
                        </div>
                        <button
                            type="submit"
                            class="btn-primary min-w-[190px]"
                            :class="sending ? 'cursor-wait opacity-95' : (!readyToSubmit() ? 'cursor-not-allowed opacity-50 hover:translate-y-0 hover:bg-pine-900' : '')"
                            :disabled="sending || !readyToSubmit()"
                            :aria-disabled="sending || !readyToSubmit()"
                        >
                            <span x-cloak x-show="!sending">Send inquiry</span>
                            <span x-cloak x-show="sending" class="inline-flex items-center gap-3">
                                <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/35 border-t-white"></span>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="surface-card">
                    <div class="eyebrow">Direct Channels</div>
                    <h3 class="mt-5 text-2xl font-semibold text-pine-950">Choose the clearest way to reach PH Haiyan.</h3>
                    <div class="mt-6 space-y-4">
                        <a href="mailto:{{ $contact['email'] }}" class="flex items-start justify-between gap-4 rounded-[24px] border border-slate-100 bg-slate-50/80 px-5 py-4 transition hover:border-pine-200 hover:bg-pine-50">
                            <div class="flex gap-4">
                                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-pine-700 ring-1 ring-pine-100">
                                    <x-icon name="mail" class="h-5 w-5" />
                                </span>
                                <div>
                                    <div class="text-sm font-semibold text-pine-950">Email the team</div>
                                    <div class="mt-1 text-sm leading-7 text-slate-600">{{ $contact['email'] }}</div>
                                </div>
                            </div>
                            <x-icon name="arrow-up-right" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                        </a>

                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}" class="flex items-start justify-between gap-4 rounded-[24px] border border-slate-100 bg-slate-50/80 px-5 py-4 transition hover:border-pine-200 hover:bg-pine-50">
                            <div class="flex gap-4">
                                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-pine-700 ring-1 ring-pine-100">
                                    <x-icon name="phone" class="h-5 w-5" />
                                </span>
                                <div>
                                    <div class="text-sm font-semibold text-pine-950">Call PH Haiyan</div>
                                    <div class="mt-1 text-sm leading-7 text-slate-600">{{ $contact['phone'] }}</div>
                                </div>
                            </div>
                            <x-icon name="arrow-up-right" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                        </a>
                    </div>
                </div>

                <div class="surface-card">
                    <div class="eyebrow">Base of Operations</div>
                    <h3 class="mt-5 text-2xl font-semibold text-pine-950">Tacloban City, Leyte</h3>
                    <div class="mt-5 space-y-4 text-sm leading-7 text-slate-600">
                        <div class="flex items-start gap-3">
                            <x-icon name="map-pin" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>{{ $contact['address'] }}</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-icon name="clock" class="mt-1 h-4 w-4 shrink-0 text-pine-700" />
                            <span>{{ $contact['hours'] }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach ($socials as $social)
                            <a href="{{ $social['href'] }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 transition hover:border-pine-200 hover:bg-pine-50 hover:text-pine-800">
                                <x-icon name="{{ $socialIcons[$social['label']] ?? 'users' }}" class="h-3.5 w-3.5" />
                                {{ $social['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="surface-card overflow-hidden p-0">
                    <div class="px-8 pt-8">
                        <div class="eyebrow">Location Map</div>
                        <h3 class="mt-5 text-2xl font-semibold text-pine-950">Find PH Haiyan in Tacloban</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">The map centers on Tacloban City, where PH Haiyan’s advocacy work is grounded and where many current initiatives are coordinated.</p>
                    </div>
                    <div class="mt-6 h-80 overflow-hidden rounded-b-[32px] border-t border-slate-100">
                        <iframe src="{{ $contact['map_embed'] }}" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
