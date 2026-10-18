<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\Customer\SettingsRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('customer.settings.index');
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('success', 'اطلاعات حساب با موفقیت بروزرسانی شد.');
=======
use App\Http\Requests\Customer\CustomerSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer.settings.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(CustomerSettingsRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if (strcasecmp($user->email, $validated['email']) !== 0) {
            // Any future email-verification flow must verify a changed address again.
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('customer.settings.index')
            ->with('success', 'اطلاعات حساب با موفقیت به‌روزرسانی شد.');
>>>>>>> 291263950635f079b586396326c2fa52fde0a0b9
    }
}
