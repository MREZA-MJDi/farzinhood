@extends('layouts.admin')

@section('title', 'ویرایش دسته‌بندی')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    ویرایش دسته‌بندی
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    اطلاعات «{{ $category->name }}» را ویرایش کنید.
                </p>
            </div>

            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                بازگشت به دسته‌بندی‌ها
            </a>

        </div>

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.categories._form', [
                'category' => $category,
                'submitLabel' => 'ذخیره تغییرات',
            ])
        </form>

    </div>
@endsection
