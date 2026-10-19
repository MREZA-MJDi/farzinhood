<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest('updated_at')
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $requestedDefault = (bool) ($data['is_default'] ?? false);
        unset($data['is_default']);

        DB::transaction(function () use ($request, $data, $requestedDefault): void {
            $user = User::query()
                ->whereKey($request->user()->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $hasDefault = $user->addresses()->where('is_default', true)->exists();
            $makeDefault = $requestedDefault || ! $hasDefault;

            if ($makeDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $data['is_default'] = $makeDefault;
            $user->addresses()->create($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'نشانی با موفقیت ذخیره شد.');
    }

    public function edit(Request $request, Address $address): View
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        return view('customer.addresses.edit', compact('address'));
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $data = $request->validated();
        $requestedDefault = (bool) ($data['is_default'] ?? false);
        unset($data['is_default']);

        DB::transaction(function () use ($request, $address, $data, $requestedDefault): void {
            $user = User::query()
                ->whereKey($request->user()->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $ownedAddress = $user->addresses()
                ->whereKey($address->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($requestedDefault) {
                $user->addresses()->update(['is_default' => false]);
                $data['is_default'] = true;
            } elseif ($ownedAddress->is_default) {
                // The current default stays default unless another address is selected.
                $data['is_default'] = true;
            } else {
                $data['is_default'] = false;
            }

            $ownedAddress->update($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'نشانی با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($request, $address): void {
            $user = User::query()
                ->whereKey($request->user()->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $ownedAddress = $user->addresses()
                ->whereKey($address->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $wasDefault = (bool) $ownedAddress->is_default;
            $ownedAddress->delete();

            if ($wasDefault) {
                $replacement = $user->addresses()->latest('updated_at')->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
                }
            }
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'نشانی حذف شد.');
    }

    public function makeDefault(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($request, $address): void {
            $user = User::query()
                ->whereKey($request->user()->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $ownedAddress = $user->addresses()
                ->whereKey($address->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $user->addresses()->update(['is_default' => false]);
            $ownedAddress->update(['is_default' => true]);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'نشانی پیش‌فرض تغییر کرد.');
    }
}
