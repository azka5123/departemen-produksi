@props(['type', 'name', 'id', 'label' => false, 'placeholder' => null, 'value' => null, 'readonly' => false])

<div class="form-group">
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        class="form-control {{ $attributes->get('class') }}"
        placeholder="{{ $placeholder }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes }}
    >
</div>
