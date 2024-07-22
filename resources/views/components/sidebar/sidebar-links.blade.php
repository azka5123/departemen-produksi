@props(['route', 'icon', 'title','baseRoute'=>null])

<li class="nav-item {{ Request::is("$baseRoute*") ? 'active' : '' }}">
    <a class="nav-link" href="{{ route($route) }}">
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </a>
</li>
