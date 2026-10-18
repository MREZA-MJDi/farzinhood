<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
<<<<<<< HEAD
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
=======
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
>>>>>>> 291263950635f079b586396326c2fa52fde0a0b9
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
<<<<<<< HEAD
    public function index(): View
    {
        $addresses = auth()->user()
            ->addresses()
            ->latest('is_default')
            ->latest('updated_at')
=======
    public function index(Request $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
>>>>>>> 291263950635f079b586396326c2fa52fde0a0b9
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
<<<<<<< HEAD
        $user = $request->user();
        $data = $request->validated();

        DB::transaction(function () use ($user, $data): void {
            $address = $user->addresses()->create($data);

            if ($data['is_default'] || $user->addresses()->count() === 1) {
                $address->makeDefault();
            }
        });

        return back()->with('success', 'آدرس با موفقیت ذخیره شد.');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $data = $request->validated();

        DB::transaction(function () use ($address, $data): void {
            $wasDefault = (bool) $address->is_default;
            $address->update($data);

            if ($data['is_default']) {
                $address->makeDefault();
                return;
            }

            if ($wasDefault) {
                $address->update(['is_default' => true]);
            }
        });

        return back()->with('success', 'آدرس با موفقیت بروزرسانی شد.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        DB::transaction(function () use ($address): void {
            $wasDefault = (bool) $address->is_default;
            $userId = $address->user_id;

            $address->delete();

            if ($wasDefault) {
                $next = Address::query()
                    ->where('user_id', $userId)
                    ->latest('updated_at')
                    ->first();

                if ($next) {
                    $next->makeDefault();
=======
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

            $wasDefault = $ownedAddress->is_default;
            $ownedAddress->delete();

            if ($wasDefault) {
                $replacement = $user->addresses()
                    ->latest()
                    ->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
>>>>>>> 291263950635f079b586396326c2fa52fde0a0b9
                }
            }
        });

<<<<<<< HEAD
        return back()->with('success', 'آدرس حذف شد.');
    }

    public function makeDefault(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        DB::transaction(function () use ($address): void {
            $address->makeDefault();
        });

        return back()->with('success', 'آدرس پیش‌فرض تغییر کرد.');
=======
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
>>>>>>> 291263950635f079b586396326c2fa52fde0a0b9
    }
}
