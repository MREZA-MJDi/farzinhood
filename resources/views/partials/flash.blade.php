@php
    $flashMessages = [
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ];
@endphp

@if(collect($flashMessages)->filter()->isNotEmpty())
    <div class="pointer-events-none fixed inset-x-4 top-4 z-[100] mx-auto max-w-xl space-y-3" aria-live="polite" aria-atomic="true">
        @foreach($flashMessages as $type => $message)
            @if($message)
                @php
                    $tone = match($type) {
                        'success' => 'success',
                        'error' => 'danger',
                        'warning' => 'warning',
                        default => 'info',
                    };
                    $titles = [
                        'success' => 'موفق',
                        'danger' => 'خطا',
                        'warning' => 'توجه',
                        'info' => 'اطلاع',
                    ];
                    $title = $titles[$tone];
                @endphp

                <div
                    class="store-flash store-flash--{{ $tone }} pointer-events-auto"
                    role="{{ $type === 'error' ? 'alert' : 'status' }}"
                    data-flash
                >
                    <span class="store-flash__icon" aria-hidden="true">
                        {{ $tone === 'success' ? '✓' : ($tone === 'danger' ? '!' : ($tone === 'warning' ? '!' : 'i')) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <strong>{{ $title }}</strong>
                        <p>{{ $message }}</p>
                    </div>
                    <button type="button" data-flash-close aria-label="بستن پیام">×</button>
                </div>
            @endif
        @endforeach
    </div>
@endif
