@extends('admin.layouts.app', ['pageTitle' => 'Contact Messages'])

@section('content')
    <section class="admin-panel">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <div class="admin-kicker">Inbox</div>
                <h2 class="admin-heading">Review incoming inquiries from the public site.</h2>
                <p class="admin-copy">Each message is saved to the database and delivered by email. Use this inbox to track status and follow-up.</p>
            </div>

            <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                <input type="search" name="q" value="{{ $search }}" placeholder="Search messages" class="min-w-[16rem] rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
                <select name="status" class="rounded-full border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-pine-300 focus:outline-none focus:ring-2 focus:ring-pine-100">
                    @foreach ($statuses as $value)
                        <option value="{{ $value }}" @selected($status === $value)>{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-secondary !px-4 !py-2.5">Filter</button>
            </form>
        </div>

        <div class="admin-table-shell mt-8">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Inquiry</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                            <tr>
                                <td>
                                    <div class="font-medium text-pine-950">{{ $message->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $message->email }}</div>
                                </td>
                                <td>{{ config('site.inquiry_types.' . $message->inquiry_type, $message->inquiry_type) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($message->subject, 70) }}</td>
                                <td>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $message->status === 'new' ? 'bg-amber-50 text-amber-700' : ($message->status === 'replied' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </td>
                                <td class="text-slate-500">{{ $message->submitted_at?->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="inline-flex items-center gap-2 rounded-full border border-pine-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-pine-900 transition hover:border-pine-300 hover:bg-pine-50">
                                            <x-icon name="eye" class="h-3.5 w-3.5" />
                                            Open
                                        </a>

                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:bg-rose-50">
                                                <x-icon name="trash" class="h-3.5 w-3.5" />
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No messages matched your current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </section>
@endsection
