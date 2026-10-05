<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\SettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
