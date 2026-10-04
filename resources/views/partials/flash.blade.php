@php
    $flashMessages = [
        'success' => session('success'),
        'error'   => session('error'),
        'warning' => session('warning'),
        'info'    => session('info'),
    ];
@endphp

@if(collect($flashMessages)->filter()->isNotEmpty())

    <div
        class="pointer-events-none fixed inset-x-4 top-4 z-[100] mx-auto max-w-xl space-y-3"
        aria-live="polite"
        aria-atomic="true"
    >

        {{-- =========================================================
            Success
        ========================================================== --}}

        @if(session('success'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 opacity-0 scale-[0.98]"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="translate-y-1 opacity-0 scale-[0.98]"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white p-4 shadow-[var(--shadow-lg)]"
                role="status"
            >

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-black text-emerald-800">
                        موفق
                    </p>

                    <p class="mt-1 text-sm leading-6 text-emerald-700">
                        {{ session('success') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-emerald-500 transition hover:bg-emerald-50 hover:text-emerald-700"
                    aria-label="بستن پیام"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m6 6 12 12"/>
                        <path d="M18 6 6 18"/>
                    </svg>
                </button>

            </div>

        @endif


        {{-- =========================================================
            Error
        ========================================================== --}}

        @if(session('error'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 opacity-0 scale-[0.98]"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="translate-y-1 opacity-0 scale-[0.98]"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-red-200 bg-white p-4 shadow-[var(--shadow-lg)]"
                role="alert"
            >

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v5"/>
                        <path d="M12 16h.01"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-black text-red-800">
                        خطا
                    </p>

                    <p class="mt-1 text-sm leading-6 text-red-700">
                        {{ session('error') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                    aria-label="بستن پیام"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m6 6 12 12"/>
                        <path d="M18 6 6 18"/>
                    </svg>
                </button>

            </div>

        @endif


        {{-- =========================================================
            Warning
        ========================================================== --}}

        @if(session('warning'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 opacity-0 scale-[0.98]"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="translate-y-1 opacity-0 scale-[0.98]"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-amber-200 bg-white p-4 shadow-[var(--shadow-lg)]"
                role="status"
            >

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="M12 3 2.8 20h18.4L12 3Z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 16h.01"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-black text-amber-800">
                        توجه
                    </p>

                    <p class="mt-1 text-sm leading-6 text-amber-700">
                        {{ session('warning') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-amber-500 transition hover:bg-amber-50 hover:text-amber-700"
                    aria-label="بستن پیام"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m6 6 12 12"/>
                        <path d="M18 6 6 18"/>
                    </svg>
                </button>

            </div>

        @endif


        {{-- =========================================================
            Info
        ========================================================== --}}

        @if(session('info'))

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 opacity-0 scale-[0.98]"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                x-transition:leave-end="translate-y-1 opacity-0 scale-[0.98]"
                class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-blue-200 bg-white p-4 shadow-[var(--shadow-lg)]"
                role="status"
            >

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 11v5"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-black text-blue-800">
                        اطلاع
                    </p>

                    <p class="mt-1 text-sm leading-6 text-blue-700">
                        {{ session('info') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-blue-500 transition hover:bg-blue-50 hover:text-blue-700"
                    aria-label="بستن پیام"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path d="m6 6 12 12"/>
                        <path d="M18 6 6 18"/>
                    </svg>
                </button>

            </div>

        @endif

    </div>

@endif
