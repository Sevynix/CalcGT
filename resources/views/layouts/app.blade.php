<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo2.png') }}">

    {{-- Page Title --}}
    <title>@yield('title', 'Growtopia Calculator') - Growtopia Calculator</title>

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Meta Description (per-page or default) --}}
    <meta name="description" content="@yield('meta_description', 'Growtopia Calculator - Free calculators and tools for Growtopia players. Track profits, manage packs, and optimize your farming strategy.')">

    {{-- Open Graph / Social Sharing --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="Growtopia Calculator">
    <meta property="og:title"       content="@yield('title', 'Growtopia Calculator') - Growtopia Calculator">
    <meta property="og:description" content="@yield('meta_description', 'Free calculators and tools for Growtopia players. Track profits, manage packs, and optimize your farming strategy.')">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ asset('images/logo.png') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="@yield('title', 'Growtopia Calculator') - Growtopia Calculator">
    <meta name="twitter:description" content="@yield('meta_description', 'Free calculators and tools for Growtopia players.')">
    <meta name="twitter:image"       content="{{ asset('images/logo.png') }}">


    {{-- Resource Hints: warm up CDN connections early --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Google Fonts: non-blocking load via print/onload swap --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>

    {{-- Compiled CSS + JS via Vite (Tailwind v4, no CDN) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Per-page preload hints (e.g. LCP images) --}}
    @yield('preload')

    {{-- Global confirm modal store (must run before Alpine initialises) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('modal', {
                open: false,
                message: '',
                type: 'reset', // 'reset' | 'table'
                _action: null,
                show(message, action, type = 'reset') {
                    this.message  = message;
                    this._action  = action;
                    this.type     = type;
                    this.open     = true;
                },
                confirm() {
                    if (this._action) this._action();
                    this.open = false;
                },
                cancel() {
                    this.open = false;
                }
            });
        });
    </script>

    {{-- Alpine.js (pinned version for reliable caching) --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js" integrity="sha512-ytM6hP1K9BkRTjUQZpxZKFjJ2TvE4QXaK7phVymsm7NimaI5H09TWWW6f2JMbonLp4ftYU6xfwQGoe3C8jta9A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Google AdSense Verification & Auto Ads --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3028975789015323"
     crossorigin="anonymous"></script>
</head>
<body class="min-h-screen bg-[#f8fafc] text-slate-800 flex font-sans antialiased" x-data="{ sidebarOpen: true, mobileNavOpen: false }">

    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen bg-transparent">
        
        @include('partials.header')

        <!-- Content Area -->
        <main class="flex-1 w-full flex flex-col">
            
            <!-- Where individual blade views render -->
            <div class="min-h-[calc(100vh-8rem)] w-full p-6 md:p-8">
                @yield('content')

                <!-- Google AdSense: Banner Bawah -->
                <div class="mt-8 w-full overflow-hidden flex justify-center">
                    <!-- banner bawah -->
                    <ins class="adsbygoogle"
                         style="display:block; width:100%; text-align:center;"
                         data-ad-client="ca-pub-3028975789015323"
                         data-ad-slot="2132986110"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
            
            @include('partials.footer')
        </main>
        
    </div>

    <!-- ======================== -->
    <!-- GLOBAL CONFIRM MODAL     -->
    <!-- ======================== -->
    <div
        x-data
        x-show="$store.modal.open"
        x-cloak
        class="fixed inset-0 z-[999] flex items-center justify-center p-4"
        @keydown.escape.window="$store.modal.cancel()"
    >
        <!-- Backdrop -->
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="$store.modal.cancel()"
        ></div>

        <!-- Panel -->
        <div
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center gap-5"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Icon -->
            <div
                class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-inner"
                :class="$store.modal.type === 'table' ? 'bg-amber-50' : 'bg-red-50'"
            >
                <svg class="w-8 h-8" :class="$store.modal.type === 'table' ? 'text-amber-500' : 'text-red-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>

            <!-- Text -->
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-1" x-text="$store.modal.type === 'table' ? 'Reset Table' : 'Reset All'"></h3>
                <p class="text-slate-500 text-sm font-medium" x-text="$store.modal.message"></p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 w-full mt-1">
                <button
                    @click="$store.modal.cancel()"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm"
                >Cancel</button>
                <button
                    @click="$store.modal.confirm()"
                    class="flex-1 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm text-white shadow-md"
                    :class="$store.modal.type === 'table' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-500 hover:bg-red-600'"
                    x-text="$store.modal.type === 'table' ? 'Yes, Reset Table' : 'Yes, Reset All'"
                ></button>
            </div>
        </div>
    </div>

    <!-- Global: prevent scroll wheel from changing number input values -->
    <script>
        document.addEventListener('wheel', function () {
            if (document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        }, { passive: true });
    </script>

    @yield('scripts')

</body>
</html>

