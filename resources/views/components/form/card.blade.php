@props(['title' => null, 'addRoute' => null, 'textColor' => 'primary'])

<div class="card shadow mb-4">
    <div {{ $attributes->merge(['class' => 'card-header py-3 d-flex justify-content-between']) }}>
        @if ($addRoute)
            <x-form.button-link :route="$addRoute" type="default" label="Add" />
            <h6 class="m-0 font-weight-bold text-{{$textColor}}">{{ $title }}</h6>
        @else
            <h6 class="m-0 font-weight-bold text-{{$textColor}}">{{ $title }}</h6>
        @endif

    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
