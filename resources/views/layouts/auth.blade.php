<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide customizer-hide" dir="ltr"
    data-skin="default" data-bs-theme="light" data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template-starter">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

    <!-- Auth page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
    @stack('styles')

    <!-- Template helpers (must be in <head>) -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>

    <!-- Content -->
    <div class="authentication-wrapper authentication-cover">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="app-brand auth-cover-brand gap-2">
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
            <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name', 'Laravel') }}</span>
        </a>
        <!-- /Logo -->

        <div class="authentication-inner row m-0">

            <!-- Right content panel -->
            {{ $slot }}
            <!-- /Right content panel -->

        </div>
    </div>
    <!-- /Content -->

    <!-- Loading overlay for navigation (BlockUI style) -->
    <div x-data="{ loading: false }" x-on:show-loader.window="loading = true" x-on:hide-loader.window="loading = false"
        x-show="loading" x-transition.opacity x-cloak
        style="position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);z-index:99999;">
        <div
            style="text-align:center;background:rgba(255,255,255,0.95);padding:30px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.15);">
            <svg width="50" height="50" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="28" stroke="#7367F0" stroke-width="6" stroke-linecap="round"
                    stroke-dasharray="44 44" stroke-dashoffset="0">
                    <animateTransform attributeName="transform" type="rotate" from="0 32 32" to="360 32 32" dur="1s"
                        repeatCount="indefinite" />
                </circle>
                <circle cx="32" cy="32" r="20" stroke="#00CFE8" stroke-width="4" stroke-linecap="round"
                    stroke-dasharray="31 31" stroke-dashoffset="0">
                    <animateTransform attributeName="transform" type="rotate" from="360 32 32" to="0 32 32" dur="1.2s"
                        repeatCount="indefinite" />
                </circle>
            </svg>
            <p style="margin-top:15px;margin-bottom:0;color:#7367F0;font-weight:500;font-size:14px;">Loading...</p>
        </div>
    </div>

    <!-- Backdrop overlay -->
    <div x-data="{ loading: false }" x-on:show-loader.window="loading = true" x-on:hide-loader.window="loading = false"
        x-show="loading" x-transition.opacity x-cloak
        style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);backdrop-filter:blur(2px);z-index:99998;">
    </div>
    <!-- /Content -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/js/main.js') }}" data-navigate-once></script>

    @livewireScripts
    @stack('scripts')

    <script>
        // Initialize password toggle functionality
        function initPasswordToggle() {
            document.querySelectorAll('.form-password-toggle').forEach(function (element) {
                const input = element.querySelector('input');
                const icon = element.querySelector('.input-group-text');

                if (input && icon) {
                    // Remove old event listener by cloning
                    const newIcon = icon.cloneNode(true);
                    icon.parentNode.replaceChild(newIcon, icon);

                    // Add new event listener
                    newIcon.addEventListener('click', function () {
                        if (input.type === 'password') {
                            input.type = 'text';
                            newIcon.querySelector('i').classList.remove('tabler-eye-off');
                            newIcon.querySelector('i').classList.add('tabler-eye');
                        } else {
                            input.type = 'password';
                            newIcon.querySelector('i').classList.remove('tabler-eye');
                            newIcon.querySelector('i').classList.add('tabler-eye-off');
                        }
                    });
                }
            });
        }

        // BlockUI-style loader for wire:navigate
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize password toggle on first load
            initPasswordToggle();

            // Listen for clicks on wire:navigate links
            document.addEventListener('click', (e) => {
                const link = e.target.closest('[wire\\:navigate]');
                if (link) {
                    // Show loader immediately
                    window.dispatchEvent(new CustomEvent('show-loader'));
                }
            });

            // Hide loader when page is ready
            window.addEventListener('load', () => {
                window.dispatchEvent(new CustomEvent('hide-loader'));
            });

            // Also hide on Livewire navigated event and re-initialize password toggle
            document.addEventListener('livewire:navigated', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('hide-loader'));
                    // Re-initialize password toggle after navigation
                    initPasswordToggle();
                }, 100);
            });
        });
    </script>

</body>

</html>