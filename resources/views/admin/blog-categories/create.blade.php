@extends('layouts.admin')

@section('title', 'افزودن دسته‌بندی وبلاگ')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    افزودن دسته‌بندی وبلاگ
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    یک دسته‌بندی جدید برای مقالات ایجاد کنید.
                </p>
            </div>

            <a href="{{ route('admin.blog-categories.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                بازگشت
            </a>

        </div>

        <form action="{{ route('admin.blog-categories.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @include('admin.blog-categories._form', [
                'category' => null,
                'submitLabel' => 'ثبت دسته‌بندی',
            ])

        </form>

    </div>
@endsection
