<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $status = trim((string) $request->query('status', 'all'));
        $search = trim((string) $request->query('q', ''));

        $messages = ContactMessage::query()
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('subject', 'like', '%'.$search.'%')
                        ->orWhere('message', 'like', '%'.$search.'%');
                });
            })
            ->latest('submitted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'status' => $status,
            'search' => $search,
            'statuses' => ['all', 'new', 'read', 'replied', 'archived'],
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
            $contactMessage->refresh();
        }

        return view('admin.contact-messages.show', [
            'message' => $contactMessage,
            'statuses' => ['new', 'read', 'replied', 'archived'],
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,archived'],
        ]);

        $contactMessage->update($data);

        return back()->with('status', 'Message status updated.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('status', 'Message deleted.');
    }
}
