<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(AddressRequest $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
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

            $hasAddresses = $user->addresses()->exists();
            $isDefault = $requestedDefault || ! $hasAddresses;

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $data['is_default'] = $isDefault;
            $user->addresses()->create($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'آدرس با موفقیت ذخیره شد.');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
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
                $replacement = $user->addresses()
                    ->where('id', '!=', $ownedAddress->getKey())
                    ->latest()
                    ->first();

                if ($replacement) {
                    $user->addresses()->update(['is_default' => false]);
                    $replacement->update(['is_default' => true]);
                    $data['is_default'] = false;
                } else {
                    // Keep one default address when this is the only saved address.
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

    public function destroy(AddressRequest $request, Address $address): RedirectResponse
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

            $wasDefault = $ownedAddress->is_default;
            $ownedAddress->delete();

            if ($wasDefault) {
                $replacement = $user->addresses()
                    ->latest()
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

    public function makeDefault(AddressRequest $request, Address $address): RedirectResponse
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
