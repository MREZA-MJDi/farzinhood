<?php

namespace AppHttpControllers;

use AppHttpRequestsCustomerSettingsRequest;
use IlluminateHttpRedirectResponse;
use IlluminateViewView;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('customer.settings.index');
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update($request->validated());

        return back()->with('success', 'اطلاعات حساب با موفقیت بروزرسانی شد.');
    }
}
