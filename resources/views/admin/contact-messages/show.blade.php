@extends('layouts.admin')

@section('title', 'جزئیات پیام')

@section('content')
@php
$statusLabels = [
'new' => 'جدید',
'read' => 'خوانده شده',
'replied' => 'پاسخ داده شده',
'closed' => 'بسته شده',
];

$statusClasses = [
'new' => 'bg-red-50 text-red-700',
'read' => 'bg-blue-50 text-blue-700',
'replied' => 'bg-emerald-50 text-emerald-700',
'closed' => 'bg-gray-100 text-gray-700',
];
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    {{ $contactMessage->subject }}
                </h1>

                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$contactMessage->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$contactMessage->status] ?? $contactMessage->status }}
                    </span>

            </div>

            <p class="mt-2 text-sm text-[var(--color-text-secondary)]">
                {{ $contactMessage->created_at->locale('fa')->translatedFormat('l، j F Y - H:i') }}
            </p>

        </div>


        <a href="{{ route('admin.contact-messages.index') }}"
           class="rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
            بازگشت
        </a>

    </div>


    {{-- Contact Info --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm lg:col-span-2">

            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                اطلاعات تماس
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <div class="text-xs text-[var(--color-text-muted)]">
                        نام
                    </div>

                    <div class="mt-1 font-bold text-[var(--color-text-primary)]">
                        {{ $contactMessage->name }}
                    </div>
                </div>


                <div>
                    <div class="text-xs text-[var(--color-text-muted)]">
                        ایمیل
                    </div>

                    <div class="mt-1 text-sm text-[var(--color-text-secondary)]"
                         dir="ltr">
                        {{ $contactMessage->email }}
                    </div>
                </div>


                @if($contactMessage->phone)
                <div>
                    <div class="text-xs text-[var(--color-text-muted)]">
                        شماره تماس
                    </div>

                    <div class="mt-1 text-sm text-[var(--color-text-secondary)]"
                         dir="ltr">
                        {{ $contactMessage->phone }}
                    </div>
                </div>
                @endif


                <div>
                    <div class="text-xs text-[var(--color-text-muted)]">
                        موضوع
                    </div>

                    <div class="mt-1 font-bold text-[var(--color-text-primary)]">
                        {{ $contactMessage->subject }}
                    </div>
                </div>

            </div>

        </div>


        {{-- Status Actions --}}
        <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

            <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                عملیات
            </h2>


            <div class="mt-5 space-y-3">

                @if($contactMessage->status === 'new')

                <form action="{{ route('admin.contact-messages.read', $contactMessage) }}"
                      method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="w-full rounded-xl bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700 hover:bg-blue-100">
                        علامت‌گذاری به عنوان خوانده شده
                    </button>

                </form>

                @endif


                @if($contactMessage->status !== 'replied' && $contactMessage->status !== 'closed')

                <form action="{{ route('admin.contact-messages.replied', $contactMessage) }}"
                      method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="w-full rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 hover:bg-emerald-100">
                        علامت‌گذاری به عنوان پاسخ داده شده
                    </button>

                </form>

                @endif


                @if($contactMessage->status !== 'closed')

                <form action="{{ route('admin.contact-messages.close', $contactMessage) }}"
                      method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="w-full rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-200">
                        بستن پیام
                    </button>

                </form>

                @endif

            </div>

        </div>

    </div>


    {{-- Message --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <h2 class="text-lg font-black text-[var(--color-text-primary)]">
            متن پیام
        </h2>

        <div class="mt-5 rounded-2xl bg-[var(--color-neutral-50)] p-5">

            <p class="whitespace-pre-line text-sm leading-8 text-[var(--color-text-secondary)]">
                {{ $contactMessage->message }}
            </p>

        </div>

    </div>


    {{-- Meta --}}
    <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            <div>

                <div class="text-xs text-[var(--color-text-muted)]">
                    تاریخ ایجاد
                </div>

                <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">
                    {{ $contactMessage->created_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                </div>

            </div>


            <div>

                <div class="text-xs text-[var(--color-text-muted)]">
                    تاریخ خواندن
                </div>

                <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">

                    @if($contactMessage->read_at)
                    {{ $contactMessage->read_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                    @else
                    —
                    @endif

                </div>

            </div>


            <div>

                <div class="text-xs text-[var(--color-text-muted)]">
                    تاریخ پاسخ
                </div>

                <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">

                    @if($contactMessage->replied_at)
                    {{ $contactMessage->replied_at->locale('fa')->translatedFormat('Y/m/d H:i') }}
                    @else
                    —
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@endsection
