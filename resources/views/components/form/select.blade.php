@props(['name', 'id', 'label', 'options', 'selected' => [], 'class' => '', 'multiple' => false])

<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <select class="form-control select2 {{ $class }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $id }}" {{ $multiple ? 'multiple' : '' }}>
        @if (!$multiple)
            <option value="">--- Pilih ---</option>
        @endif
        @foreach ($options as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ in_array($optionKey, (array) $selected) ? 'selected' : '' }}>
                {{ $optionValue }}
            </option>
        @endforeach
    </select>
</div>
