<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()
            ->addresses()
            ->latest('is_default')
            ->latest('updated_at')
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
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
                }
            }
        });

        return back()->with('success', 'آدرس حذف شد.');
    }

    public function makeDefault(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        DB::transaction(function () use ($address): void {
            $address->makeDefault();
        });

        return back()->with('success', 'آدرس پیش‌فرض تغییر کرد.');
    }
}
