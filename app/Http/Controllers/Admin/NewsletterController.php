<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(NewsletterIndexRequest $request)
    {
        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(
                    'email',
                    'like',
                    '%' . $request->input('search') . '%'
                );
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'is_active',
                    $request->input('status') === 'active'
                );
            })
            ->latest()
            ->paginate(20);

        return view('admin.newsletter.index', compact('subscribers'));
    }
    public function activate(
        NewsletterSubscriber $newsletterSubscriber
    ): RedirectResponse {
        $newsletterSubscriber->subscribe();

        return back()->with(
            'success',
            'Subscriber activated successfully.'
        );
    }

    public function deactivate(
        NewsletterSubscriber $newsletterSubscriber
    ): RedirectResponse {
        $newsletterSubscriber->unsubscribe();

        return back()->with(
            'success',
            'Subscriber unsubscribed successfully.'
        );
    }

    public function destroy(
        NewsletterSubscriber $newsletterSubscriber
    ): RedirectResponse {
        $newsletterSubscriber->delete();

        return redirect()
            ->route('admin.newsletter.index')
            ->with(
                'success',
                'Subscriber deleted successfully.'
            );
    }
}
