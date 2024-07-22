@props(['route', 'method' => 'GET', 'id' => null])

<form action="{{ is_string($route) ? route($route, $id) : $route }}" method="{{ $method }}">
    @csrf
    {{ $slot }}
</form>
