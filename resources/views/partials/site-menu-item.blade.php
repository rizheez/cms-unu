@php
    $children = $item->childrenRecursive;
    $hasChildren = $children->isNotEmpty();
@endphp

<li class="site-nav-item {{ $hasChildren ? 'has-children' : '' }}">
    <a href="{{ $item->resolvedUrl() }}" target="{{ $item->target }}" @if ($item->target === '_blank') rel="noopener noreferrer" @endif>
        <span>{{ $item->label }}</span>
        @if ($hasChildren)
            <span class="menu-caret" aria-hidden="true">v</span>
        @endif
    </a>

    @if ($hasChildren)
        <button
            class="mobile-submenu-toggle"
            type="button"
            aria-expanded="false"
            aria-label="Buka submenu {{ $item->label }}">
            <span class="menu-caret" aria-hidden="true">v</span>
        </button>
        <ul class="site-dropdown">
            @foreach ($children as $child)
                @include('partials.site-menu-item', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>
