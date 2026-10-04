@php
    $accountLinks = [
        ['route' => 'customer.dashboard', 'label' => 'داشبورد', 'active' => request()->routeIs('customer.dashboard')],
        ['route' => 'customer.orders.index', 'label' => 'سفارش‌ها', 'active' => request()->routeIs('customer.orders.*')],
        ['route' => 'customer.wishlist.index', 'label' => 'علاقه‌مندی‌ها', 'active' => request()->routeIs('customer.wishlist.*')],
        ['route' => 'customer.addresses.index', 'label' => 'آدرس‌ها', 'active' => request()->routeIs('customer.addresses.*')],
        ['route' => 'customer.settings.index', 'label' => 'تنظیمات', 'active' => request()->routeIs('customer.settings.*')],
    ];
@endphp

<nav class="store-account-nav" aria-label="ناوبری حساب کاربری">
    @foreach($accountLinks as $link)
        <a href="{{ route($link['route']) }}" class="{{ $link['active'] ? 'is-active' : '' }}">
            <span>{{ $link['label'] }}</span>
            <span aria-hidden="true">←</span>
        </a>
    @endforeach

    <div class="store-account-nav__logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">خروج از حساب</button>
        </form>
    </div>
</nav>
