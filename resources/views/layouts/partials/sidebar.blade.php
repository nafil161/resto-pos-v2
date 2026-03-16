@php
// Detect which app (if any) the user is currently inside,
// based on the current route name prefix matching an app slug.
$currentRoute = request()->route()?->getName() ?? '';
$appsConfig = config('apps', []);
$currentApp = null;

foreach ($appsConfig as $slug => $app) {
if (str_starts_with($currentRoute, $slug . '.') || $currentRoute === $slug) {
$currentApp = $app;
$currentApp['slug'] = $slug;
break;
}
}
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                            fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">{{ config('app.name', 'Laravel') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        @if ($currentApp)
        {{-- ── Inside an App ─────────────────────────────── --}}

        {{-- Back to Dashboard --}}
        <li class="menu-item">
            <a href="{{ route('dashboard') }}" wire:navigate class="menu-link">
                <i class="menu-icon icon-base ti tabler-arrow-left"></i>
                <div data-i18n="Back">Back to Dashboard</div>
            </a>
        </li>

        {{-- App name header --}}
        <li class="menu-header small text-uppercase mt-1">
            <span class="menu-header-text d-flex align-items-center gap-2">
                <i class="ti {{ $currentApp['icon'] }}"></i>
                {{ $currentApp['name'] }}
            </span>
        </li>

        {{-- App-specific menu items --}}
        @foreach ($currentApp['sidebar'] as $item)
        <li class="menu-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
            <a href="{{ route($item['route']) }}" wire:navigate class="menu-link">
                <i class="menu-icon icon-base ti {{ $item['icon'] }}"></i>
                <div>{{ $item['label'] }}</div>
            </a>
        </li>
        @endforeach

        @else
        {{-- ── Dashboard / Default ──────────────────────── --}}

        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" wire:navigate class="menu-link">
                <i class="menu-icon icon-base ti tabler-apps"></i>
                <div data-i18n="Apps">Apps</div>
            </a>
        </li>

        @endif

    </ul>
</aside>