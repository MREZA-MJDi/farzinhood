@props([
'title',
'description' => null,
'icon' => null,
])

<div
    {{ $attributes->merge([
        'class' => 'empty-state',
        'role' => 'status',
    ]) }}
>

    @if($icon)
        <div
            class="empty-state__icon"
            aria-hidden="true"
        >
            {{ $icon }}
        </div>
    @endif

    <div class="empty-state__content">

        <h2 class="empty-state__title">
            {{ $title }}
        </h2>

        @if($description)
            <p class="empty-state__description">
                {{ $description }}
            </p>
        @endif

    </div>

    @isset($action)
        <div class="empty-state__action">
            {{ $action }}
        </div>
    @endisset

</div>
