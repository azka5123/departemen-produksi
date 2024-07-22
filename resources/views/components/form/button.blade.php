@props(['type' => 'submit', 'label', 'style' => 'btn-primary', 'buttonPosition' => 'd-flex justify-content-end'])

<div class="{{ $buttonPosition }}">
    <button {{ $attributes->merge(['type' => $type, 'class' => "btn $style"]) }}>
        {{ $label }}
    </button>
</div>
