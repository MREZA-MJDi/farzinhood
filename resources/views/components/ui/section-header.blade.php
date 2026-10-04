@props([
'eyebrow' => null,
'title',
'description' => null,
'titleId' => null,
'centered' => false,
])

<header
    {{ $attributes->merge([
        'class' => 'section-header' . ($centered ? ' section-header--center' : ''),
    ]) }}
>
    <div class="section-header__content">

        @if($eyebrow)
            <span class="section-header__eyebrow">
                {{ $eyebrow }}
            </span>
        @endif

        <h2
            @if($titleId)
            id="{{ $titleId }}"
            @endif
            class="section-header__title"
        >
            {{ $title }}
        </h2>

        @if($description)
            <p class="section-header__description">
                {{ $description }}
            </p>
        @endif

    </div>

    @isset($action)
        <div class="section-header__action">
            {{ $action }}
        </div>
    @endisset

</header>
