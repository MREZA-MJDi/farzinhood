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
        unset($data['user_id']);

        $requestedDefault = (bool) ($data['is_default'] ?? false);

        DB::transaction(function () use ($request, $data, $requestedDefault): void {
            $user = User::query()
                ->whereKey($request->user()->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $shouldBeDefault = $requestedDefault || ! $user->addresses()->exists();

            if ($shouldBeDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $data['is_default'] = $shouldBeDefault;
            $user->addresses()->create($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'آدرس با موفقیت ذخیره شد.');
    }

    public function edit(Request $request, Address $address): View
    {
        $ownedAddress = $request->user()
            ->addresses()
            ->whereKey($address->getKey())
            ->firstOrFail();

        return view('customer.addresses.edit', [
            'address' => $ownedAddress,
        ]);
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $data = $request->validated();
        unset($data['user_id']);

        $requestedDefault = (bool) ($data['is_default'] ?? false);

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
                $replacement = $user->addresses()
                    ->whereKeyNot($ownedAddress->getKey())
                    ->latest('updated_at')
                    ->first();

                if ($replacement) {
                    $user->addresses()->update(['is_default' => false]);
                    $replacement->update(['is_default' => true]);
                    $data['is_default'] = false;
                } else {
                    // Never leave a customer without a default when only one address exists.
                    $data['is_default'] = true;
                }
            } else {
                $data['is_default'] = false;
            }

            $ownedAddress->update($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'آدرس با موفقیت ویرایش شد.');
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
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
                $user->addresses()->update(['is_default' => false]);

                $replacement = $user->addresses()
                    ->latest('updated_at')
                    ->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
                }
            }
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'آدرس حذف شد.');
    }

    public function makeDefault(Request $request, Address $address): RedirectResponse
    {
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
            ->with('success', 'آدرس پیش‌فرض تغییر کرد.');
    }
}
