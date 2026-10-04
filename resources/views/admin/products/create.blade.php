@extends('layouts.admin')

@section('title', 'افزودن محصول')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    افزودن محصول
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    یک محصول جدید به فروشگاه اضافه کنید.
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] transition hover:bg-[var(--color-neutral-50)]">
                بازگشت به محصولات
            </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.products.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            @include('admin.products._form', [
                'product' => null,
                'submitLabel' => 'ثبت محصول',
            ])
        </form>

    </div>
@endsection
