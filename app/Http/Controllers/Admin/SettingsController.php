<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::query()
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        return view(
            'admin.settings.index',
            compact('settings')
        );
    }

    public function update(
        SettingsRequest $request
    ): RedirectResponse {
        foreach ($request->validated() as $key => $value) {
            SiteSetting::setValue(
                $key,
                $value,
                'string'
            );
        }

        return back()->with(
            'success',
            'Settings updated successfully.'
        );
    }
}
