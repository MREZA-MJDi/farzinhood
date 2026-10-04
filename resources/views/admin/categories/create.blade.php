@extends('layouts.admin')

@section('title', 'افزودن دسته‌بندی')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    افزودن دسته‌بندی
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    یک دسته‌بندی جدید برای محصولات ایجاد کنید.
                </p>
            </div>

            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                بازگشت به دسته‌بندی‌ها
            </a>

        </div>

        <form action="{{ route('admin.categories.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            @include('admin.categories._form', [
                'category' => null,
                'submitLabel' => 'ثبت دسته‌بندی',
            ])
        </form>

    </div>
@endsection
