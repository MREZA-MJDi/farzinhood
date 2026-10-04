<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\NewsletterRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function store(NewsletterRequest $request): RedirectResponse|JsonResponse
    {
        $email = $request->validated('email');

        $subscriber = NewsletterSubscriber::query()
            ->where('email', $email)
            ->first();

        if ($subscriber) {
            $subscriber->update([
                'is_active' => true,
                'subscribed_at' => $subscriber->subscribed_at ?? now(),
                'unsubscribed_at' => null,
            ]);
        } else {
            NewsletterSubscriber::create([
                'email' => $email,
                'is_active' => true,
                'subscribed_at' => now(),
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have subscribed successfully.',
            ]);
        }

        return back()->with(
            'success',
            'You have subscribed successfully.'
        );
    }
}
