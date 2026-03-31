@extends('admin.layouts.app', ['pageTitle' => 'Message from ' . $message->name])

@section('content')
    <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <article class="admin-panel">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="admin-kicker">Contact record</div>
                    <h2 class="admin-heading mt-2">{{ $message->subject }}</h2>
                    <p class="admin-copy mt-3">Submitted {{ $message->submitted_at?->format('M d, Y h:i A') }}</p>
                </div>

                <a href="{{ route('admin.messages.index') }}" class="btn-secondary !px-4 !py-2.5">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Back to inbox
                </a>
            </div>

            <dl class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="admin-panel-subtle px-5 py-4">
                    <dt class="admin-label">Name</dt>
                    <dd class="mt-2 text-base font-medium text-slate-900">{{ $message->name }}</dd>
                </div>
                <div class="admin-panel-subtle px-5 py-4">
                    <dt class="admin-label">Email</dt>
                    <dd class="mt-2 text-base font-medium text-slate-900">{{ $message->email }}</dd>
                </div>
                <div class="admin-panel-subtle px-5 py-4">
                    <dt class="admin-label">Phone</dt>
                    <dd class="mt-2 text-base font-medium text-slate-900">{{ $message->phone ?: 'Not provided' }}</dd>
                </div>
                <div class="admin-panel-subtle px-5 py-4">
                    <dt class="admin-label">Organization</dt>
                    <dd class="mt-2 text-base font-medium text-slate-900">{{ $message->organization ?: 'Not provided' }}</dd>
                </div>
                <div class="admin-panel-subtle px-5 py-4 md:col-span-2">
                    <dt class="admin-label">Inquiry type</dt>
                    <dd class="mt-2 text-base font-medium text-slate-900">{{ config('site.inquiry_types.' . $message->inquiry_type, $message->inquiry_type) }}</dd>
                </div>
            </dl>

            <div class="admin-panel-subtle mt-8 px-6 py-5">
                <div class="admin-label">Message</div>
                <div class="mt-4 whitespace-pre-line text-base leading-8 text-slate-700">{{ $message->message }}</div>
            </div>
        </article>

        <aside class="space-y-6">
            <section class="admin-panel">
                <div class="admin-kicker">Status</div>
                <form action="{{ route('admin.messages.update', $message) }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="admin-select">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($message->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary w-full justify-center">
                        <x-icon name="save" class="h-4 w-4" />
                        Update status
                    </button>
                </form>
            </section>

            <section class="admin-panel">
                <div class="admin-kicker">Quick actions</div>
                <div class="mt-5 space-y-3">
                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="btn-secondary w-full justify-center">
                        <x-icon name="mail" class="h-4 w-4" />
                        Reply by email
                    </a>
                    @if ($message->phone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $message->phone) }}" class="btn-secondary w-full justify-center">
                            <x-icon name="phone" class="h-4 w-4" />
                            Call sender
                        </a>
                    @endif

                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-50">
                            <x-icon name="trash" class="h-4 w-4" />
                            Delete message
                        </button>
                    </form>
                </div>
            </section>
        </aside>
    </section>
@endsection
