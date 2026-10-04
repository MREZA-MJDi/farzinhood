<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::query()
            ->latest()
            ->paginate(20);

        return view(
            'admin.contact-messages.index',
            compact('messages')
        );
    }

    public function show(
        ContactMessage $contactMessage
    ): View {
        if ($contactMessage->status === 'new') {
            $contactMessage->markAsRead();
        }

        return view(
            'admin.contact-messages.show',
            compact('contactMessage')
        );
    }

    public function markAsRead(
        ContactMessage $contactMessage
    ): RedirectResponse {
        $contactMessage->markAsRead();

        return back()->with(
            'success',
            'Message marked as read.'
        );
    }

    public function markAsReplied(
        ContactMessage $contactMessage
    ): RedirectResponse {
        $contactMessage->markAsReplied();

        return back()->with(
            'success',
            'Message marked as replied.'
        );
    }

    public function close(
        ContactMessage $contactMessage
    ): RedirectResponse {
        $contactMessage->update([
            'status' => 'closed',
        ]);

        return back()->with(
            'success',
            'Message closed successfully.'
        );
    }

    public function destroy(
        ContactMessage $contactMessage
    ): RedirectResponse {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with(
                'success',
                'Message deleted successfully.'
            );
    }
}
