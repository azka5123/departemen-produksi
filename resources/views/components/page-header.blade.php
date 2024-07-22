@props(['title'])

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 {{ $attributes->merge(['class' => 'h3 mb-0 text-gray-800']) }}>{{ $title }}</h1>
</div>
