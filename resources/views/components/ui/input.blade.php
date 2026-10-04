@props([
'name',
'label' => null,
'type' => 'text',
'placeholder' => null,
'value' => null,
'required' => false,
'disabled' => false,
'help' => null,
'error' => null,
'iconStart' => null,
'iconEnd' => null,
])

@php
    $inputId = $attributes->get('id', $name);
@endphp

<div class="form-field {{ $error ? 'form-field--error' : '' }}">

    @if($label)
        <label
            for="{{ $inputId }}"
            class="form-field__label"
        >
            {{ $label }}

            @if($required)
                <span
                    class="form-field__required"
                    aria-hidden="true"
                >
                    *
                </span>
            @endif
        </label>
    @endif


    <div class="
        form-field__control
        {{ $iconStart ? 'form-field__control--icon-start' : '' }}
    {{ $iconEnd ? 'form-field__control--icon-end' : '' }}
        ">

        @if($iconStart)
            <span
                class="form-field__icon form-field__icon--start"
                aria-hidden="true"
            >
                {!! $iconStart !!}
            </span>
        @endif


        <input
            id="{{ $inputId }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="form-input"
            @required($required)
            @disabled($disabled)
            aria-invalid="{{ $error ? 'true' : 'false' }}"
            @if($error)
            aria-describedby="{{ $inputId }}-error"
            @elseif($help)
            aria-describedby="{{ $inputId }}-help"
            @endif
            {{ $attributes->except(['id', 'class']) }}
        >


        @if($iconEnd)
            <span
                class="form-field__icon form-field__icon--end"
                aria-hidden="true"
            >
                {!! $iconEnd !!}
            </span>
        @endif

    </div>


    @if($error)

        <span
            id="{{ $inputId }}-error"
            class="form-field__error"
        >
            {{ $error }}
        </span>

    @elseif($help)

        <span
            id="{{ $inputId }}-help"
            class="form-field__help"
        >
            {{ $help }}
        </span>

    @endif

</div>
