<?php

namespace AppHttpControllers;

use AppHttpRequestsCustomerAddressRequest;
use AppModelsAddress;
use IlluminateHttpRedirectResponse;
use IlluminateViewView;

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
        $user = auth()->user();
        $data = $request->validated();

        $isFirstAddress = ! $user->addresses()->exists();

        $data['country'] = $data['country'] ?? 'ایران';
        $data['is_default'] = $isFirstAddress
            || (bool) ($data['is_default'] ?? false);

        $address = $user->addresses()->create($data);

        if ($address->is_default) {
            $address->makeDefault();
        }

        return back()->with('success', 'آدرس با موفقیت ذخیره شد.');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $data = $request->validated();

        $address->update($data);

        if ($address->is_default) {
            $address->makeDefault();
        }

        return back()->with('success', 'آدرس با موفقیت بروزرسانی شد.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            auth()->user()
                ->addresses()
                ->latest('id')
                ->first()
                ?->makeDefault();
        }

        return back()->with('success', 'آدرس حذف شد.');
    }

    public function makeDefault(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $address->makeDefault();

        return back()->with('success', 'آدرس پیش‌فرض تغییر کرد.');
    }
}
