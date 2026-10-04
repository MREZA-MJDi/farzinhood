<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
    }
}
