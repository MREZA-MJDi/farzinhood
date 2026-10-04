@extends('layouts.admin')

@section('title', 'ویرایش نوشته')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                    ویرایش نوشته
                </h1>

                <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                    ویرایش مقاله «{{ $post->title }}»
                </p>
            </div>

            <a href="{{ route('admin.posts.index') }}"
               class="rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                بازگشت به نوشته‌ها
            </a>

        </div>


        <form action="{{ route('admin.posts.update', $post) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('admin.posts._form', [
                'post' => $post,
                'submitLabel' => 'ذخیره تغییرات',
            ])

        </form>

    </div>
@endsection
