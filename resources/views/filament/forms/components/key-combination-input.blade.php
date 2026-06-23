@php
    $fieldWrapperView = $getFieldWrapperView();
    $extraAttributeBag = $getExtraAttributeBag();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixIconColor = $getPrefixIconColor();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixIconColor = $getSuffixIconColor();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
    $placeholder = $getPlaceholder();
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    <div
        x-data="keyCombinationInput({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            isDisabled: @js($isDisabled),
        })"
        class="w-full"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :inline-prefix="$isPrefixInline"
            :inline-suffix="$isSuffixInline"
            :prefix="$prefixLabel"
            :prefix-actions="$prefixActions"
            :prefix-icon="$prefixIcon"
            :prefix-icon-color="$prefixIconColor"
            :suffix="$suffixLabel"
            :suffix-actions="$suffixActions"
            :suffix-icon="$suffixIcon"
            :suffix-icon-color="$suffixIconColor"
            :valid="! $errors->has($statePath)"
            :attributes="
                \Filament\Support\prepare_inherited_attributes($extraAttributeBag)
                    ->class(['fi-fo-key-combination-input'])
            "
        >
            <input
                x-ref="input"
                id="{{ $id }}"
                type="text"
                readonly
                :value="displayValue"
                x-on:keydown="capture($event)"
                x-on:click="focusInput()"
                x-on:focus="listening = true"
                x-on:blur="listening = false"
                placeholder="{{ filled($placeholder) ? e($placeholder) : 'Click here and press your shortcut keys' }}"
                @disabled($isDisabled)
                @class([
                    'fi-input',
                    'fi-input-has-inline-prefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                    'fi-input-has-inline-suffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                ])
                x-bind:class="listening && ! isDisabled ? 'ring-2 ring-primary-600 dark:ring-primary-500' : ''"
            />
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
