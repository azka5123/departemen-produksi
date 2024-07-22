@props(['route', 'id' => null, 'type' => 'default', 'label'])

@php
    $classMap = [
        'default' => 'btn-primary',
        'edit' => 'btn-warning',
        'delete' => 'btn-danger',
    ];

    $class = $classMap[$type] ?? 'btn-primary';

    $href = is_string($route) ? route($route, $id) : $route;

    $linkAttributes = [
        'href' => $href,
        'class' => "btn btn-sm $class",
    ];
@endphp

<a {!! implode(' ', array_map(fn($v, $k) => "$k=\"$v\"", $linkAttributes, array_keys($linkAttributes))) !!}>{{ $label }}</a>
