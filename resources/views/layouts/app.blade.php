<!DOCTYPE html>
<html lang="en" id="html-root">
<script>if(localStorage.getItem('gtcalc-dark')==='true')document.getElementById('html-root').classList.add('dark');</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo2.png') }}">

    {{-- Page Title --}}
    <title>@yield('title', 'Growtopia Calculator') - Growtopia Calculator</title>

    {{-- Canonical URL (always use www) --}}
    <link rel="canonical" href="{{ str_replace('://growtopiacalculator.com', '://www.growtopiacalculator.com', url()->current()) }}">

    {{-- Meta Description (per-page or default) --}}
    <meta name="description" content="@yield('meta_description', 'Growtopia Calculator - Free calculators and tools for Growtopia players. Track profits, manage packs, and optimize your farming strategy.')">

    {{-- Open Graph / Social Sharing --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="Growtopia Calculator">
    <meta property="og:title"       content="@yield('title', 'Growtopia Calculator') - Growtopia Calculator">
    <meta property="og:description" content="@yield('meta_description', 'Free calculators and tools for Growtopia players. Track profits, manage packs, and optimize your farming strategy.')">
    <meta property="og:url"         content="{{ str_replace('://growtopiacalculator.com', '://www.growtopiacalculator.com', url()->current()) }}">
    <meta property="og:image"       content="{{ asset('images/logofix.png') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="@yield('title', 'Growtopia Calculator') - Growtopia Calculator">
    <meta name="twitter:description" content="@yield('meta_description', 'Free calculators and tools for Growtopia players.')">
    <meta name="twitter:image"       content="{{ asset('images/logofix.png') }}">


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
                title: 'Konfirmasi',
                confirmText: 'Ya',
                type: 'danger', // 'danger' | 'warning' | 'reset' | 'table'
                _action: null,
                show(message, action, arg3 = 'Konfirmasi', confirmText = 'Ya', type = 'danger') {
                    if (arg3 === 'table' || arg3 === 'reset') {
                        this.type = arg3;
                        this.title = arg3 === 'table' ? 'Reset Table' : 'Reset All';
                        this.confirmText = arg3 === 'table' ? 'Yes, Reset Table' : 'Yes, Reset All';
                    } else {
                        this.title       = arg3;
                        this.confirmText = confirmText;
                        this.type        = type;
                    }
                    this.message     = message;
                    this._action     = action;
                    this.open        = true;
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

    @stack('styles')
</head>
<body class="min-h-screen text-slate-800 dark:text-slate-200 flex font-sans antialiased" x-data="{ sidebarOpen: true, mobileNavOpen: false, darkMode: localStorage.getItem('gtcalc-dark') === 'true' }" x-init="$watch('darkMode', v => { localStorage.setItem('gtcalc-dark', v); if(document.startViewTransition) { document.startViewTransition(() => document.getElementById('html-root').classList.toggle('dark', v)); } else { document.getElementById('html-root').classList.toggle('dark', v); } })">

    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen bg-transparent">
        
        @include('partials.header')

        <!-- Content Area -->
        <main class="flex-1 w-full flex flex-col">
            
            <!-- Where individual blade views render -->
            <div class="min-h-[calc(100vh-8rem)] w-full p-6 md:p-8">
                @yield('content')

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
                <h3 class="text-xl font-extrabold text-slate-800 mb-1" x-text="$store.modal.title"></h3>
                <p class="text-slate-500 text-sm font-medium" x-text="$store.modal.message"></p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 w-full mt-1">
                <button
                    @click="$store.modal.cancel()"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm"
                >Batal</button>
                <button
                    @click="$store.modal.confirm()"
                    class="flex-1 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm text-white shadow-md"
                    :class="$store.modal.type === 'warning' || $store.modal.type === 'table' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-500 hover:bg-red-600'"
                    x-text="$store.modal.confirmText"
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

    {{-- ============================================================ --}}
    {{-- ONE-TIME "WHAT'S NEW" WELCOME MODAL (direct body child)      --}}
    {{-- ============================================================ --}}
    @php
        $modalSetting = \App\Models\Setting::where('key', 'whats_new_modal')->first();
        $modalData = null;
        if ($modalSetting) {
            $modalData = json_decode($modalSetting->value, true);
        }
    @endphp

    @if($modalData && !empty($modalData['is_enabled']))
    {{-- Sparkle keyframes + modal CSS --}}
    <style>
        @keyframes wn-float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33%       { transform: translateY(-6px) rotate(4deg); }
            66%       { transform: translateY(-3px) rotate(-3deg); }
        }
        @keyframes wn-spin-slow { to { transform: rotate(360deg); } }
        @keyframes wn-ping-soft {
            0%, 100% { transform: scale(1);   opacity: 1; }
            50%       { transform: scale(1.4); opacity: 0; }
        }
        @keyframes wn-slide-in {
            from { opacity: 0; transform: translateX(-10px); }
            to   { opacity: 1; transform: translateX(0);     }
        }
        .wn-float      { animation: wn-float 3.5s ease-in-out infinite; }
        .wn-float-slow { animation: wn-float 5s ease-in-out infinite; animation-delay: 1s; }
        .wn-spin       { animation: wn-spin-slow 8s linear infinite; }
        .wn-ping       { animation: wn-ping-soft 1.8s ease-in-out infinite; }
        .wn-row-anim { animation: wn-slide-in 0.3s ease both; }
        .wn-btn-hover:hover { filter: brightness(1.08); transform: scale(1.02); }
        .wn-btn-hover { transition: filter 0.15s, transform 0.15s; }
        .wn-dismiss:hover { color: #475569; background: #f1f5f9; }
        .wn-dismiss { transition: color 0.15s, background 0.15s; }
    </style>

    <div id="whats-new-modal"
         style="display:none; position:fixed; inset:0; z-index:99999; display:flex; align-items:center; justify-content:center; padding:16px;"
         aria-modal="true" role="dialog" aria-labelledby="whats-new-title">

        {{-- Backdrop --}}
        <div id="whats-new-backdrop"
             style="position:absolute; inset:0; background:rgba(15,23,42,0.72); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);">
        </div>

        {{-- Panel --}}
        <div id="whats-new-panel"
             style="position:relative; background:#fff; border-radius:28px; box-shadow:0 32px 80px -8px rgba(15,23,42,0.28), 0 0 0 1px rgba(148,163,184,0.1); width:100%; max-width:360px; max-height:90vh; overflow-y:auto; opacity:0; transform:scale(0.88) translateY(16px); transition:opacity 0.3s cubic-bezier(0.34,1.56,0.64,1), transform 0.3s cubic-bezier(0.34,1.56,0.64,1);">

            {{-- ── Hero banner ── --}}
            <div style="position:relative; overflow:hidden; border-radius:28px 28px 0 0; background:linear-gradient(135deg, #0c2a6e 0%, #0e59c3 55%, #4b84e0 100%); padding:28px 24px 24px; text-align:center;">

                {{-- Close X Button --}}
                <button id="whats-new-close-x" style="position:absolute; top:12px; right:12px; width:30px; height:30px; border-radius:50%; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color:white; cursor:pointer; backdrop-filter:blur(4px); z-index:20;" class="wn-btn-hover" aria-label="Close">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Decorative circles --}}
                <div style="position:absolute; top:-24px; right:-24px; width:120px; height:120px; border-radius:50%; background:rgba(75,132,224,0.22);"></div>
                <div style="position:absolute; bottom:-16px; left:-16px; width:80px; height:80px; border-radius:50%; background:rgba(14,89,195,0.18);"></div>

                {{-- Floating sparkle dots --}}
                <span class="wn-spin" style="position:absolute; top:14px; left:20px; font-size:18px; opacity:0.7;"><img src="{{ asset('images/items/gems.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>
                <span class="wn-float-slow" style="position:absolute; top:10px; right:30px; font-size:13px; opacity:0.6;"><img src="{{ asset('images/items/gems.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>
                <span class="wn-float" style="position:absolute; bottom:18px; right:18px; font-size:10px; opacity:0.5; color:#93c5fd;">●</span>

                {{-- Icon pair --}}
                <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:14px;">
                    <div class="wn-float" style="width:52px; height:52px; border-radius:16px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
                        <img loading="lazy" src="{{ asset('images/items/guestbook.png') }}" alt="Updates" style="width:32px;height:32px;object-fit:contain;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                    </div>
                    <div style="width:4px; height:4px; border-radius:50%; background:rgba(255,255,255,0.3);"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:rgba(255,255,255,0.2);"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:rgba(255,255,255,0.3);"></div>
                    <div class="wn-float-slow" style="width:52px; height:52px; border-radius:16px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
                        <img loading="lazy" src="{{ asset('images/items/megaphone.png') }}" alt="Community" style="width:32px;height:32px;object-fit:contain;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
                    </div>
                </div>

                {{-- Badge --}}
                <div style="display:inline-flex; align-items:center; gap:5px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:99px; padding:3px 10px; margin-bottom:8px;">
                    <span class="wn-ping" style="width:6px; height:6px; border-radius:50%; background:#86efac; display:inline-block;"></span>
                    <span style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.85); letter-spacing:0.08em; text-transform:uppercase;">What's New <img src="{{ asset('images/items/gems.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>
                </div>

                {{-- Headline --}}
                <h3 id="whats-new-title" style="margin:0 0 6px; font-size:22px; font-weight:800; color:#fff; line-height:1.25; letter-spacing:-0.01em;">
                    {{ $modalData['title_normal'] ?? 'Updates & Community' }}<br>
                    <span style="background:linear-gradient(90deg,#bfdbfe,#93c5fd); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">{{ $modalData['title_highlight'] ?? 'are now live!' }}</span>
                </h3>
                <p style="margin:0; font-size:13px; color:rgba(255,255,255,0.65); font-weight:500; line-height:1.5;">{{ $modalData['description'] ?? 'Fresh patch notes & community insights waiting for you.' }}</p>
            </div>

            {{-- ── Body ── --}}
            <div style="padding:20px 20px 16px; display:flex; flex-direction:column; gap:12px;">

                {{-- Feature rows --}}
                @if(!empty($modalData['features']))
                    @foreach($modalData['features'] as $index => $feature)
                    <div class="wn-row-anim" style="animation-delay:{{ 0.05 + ($index * 0.07) }}s; display:flex; align-items:center; gap:12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:12px 14px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#edf4fc,#dbe8f9); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg style="width:18px;height:18px;" fill="none" stroke="#4b84e0" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                            </svg>
                        </div>
                        <div>
                            <p style="margin:0 0 1px; font-size:13px; font-weight:700; color:#1e293b;">{{ $feature['title'] }}</p>
                            <p style="margin:0; font-size:11px; color:#94a3b8; font-weight:500;">{{ $feature['description'] }}</p>
                        </div>
                        @if(!empty($feature['badge']))
                        <div style="margin-left:auto; font-size:10px; font-weight:700; color:#0e59c3; background:#dbe8f9; padding:2px 8px; border-radius:99px; white-space:nowrap;">{{ $feature['badge'] }}</div>
                        @endif
                    </div>
                    @endforeach
                @endif

                {{-- Buttons --}}
                <div style="display:flex; flex-direction:column; gap:8px; margin-top:4px;">
                    @if(!empty($modalData['buttons']))
                        @foreach($modalData['buttons'] as $index => $button)
                        <a href="{{ $button['url'] }}" class="wn-btn-hover whats-new-action-btn"
                           style="display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg,{{ $button['bg_from'] ?? '#4b84e0' }},{{ $button['bg_to'] ?? '#0e59c3' }}); color:#fff; font-size:14px; font-weight:700; padding:13px; border-radius:14px; text-decoration:none; box-shadow:0 4px 14px rgba(75,132,224,0.35);">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                            </svg>
                            {{ $button['text'] }}
                        </a>
                        @endforeach
                    @endif

                    <button id="whats-new-dismiss" class="wn-dismiss"
                            style="width:100%; background:none; border:none; color:#94a3b8; font-size:12px; font-weight:600; padding:9px; border-radius:10px; cursor:pointer;">
                        Maybe later
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        'use strict';
        var VERSION_KEY = '{{ $modalData['version_key'] ?? 'v1' }}';
        var MODAL_KEY = 'gtcalc_whats_new_dismissed_' + VERSION_KEY;
        var modal    = document.getElementById('whats-new-modal');
        var panel    = document.getElementById('whats-new-panel');
        var dismiss  = document.getElementById('whats-new-dismiss');
        var closeX   = document.getElementById('whats-new-close-x');
        var backdrop = document.getElementById('whats-new-backdrop');

        // Always hide on init first (overrides the display:flex in the attribute)
        if (modal) modal.style.display = 'none';

        if (!localStorage.getItem(MODAL_KEY) && modal) {
            setTimeout(function () {
                modal.style.display = 'flex';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        panel.style.opacity = '1';
                        panel.style.transform = 'scale(1) translateY(0)';
                    });
                });
            }, 800);

            function closeModal() {
                localStorage.setItem(MODAL_KEY, '1');
                panel.style.opacity = '0';
                panel.style.transform = 'scale(0.88) translateY(16px)';
                setTimeout(function () { modal.style.display = 'none'; }, 300);
            }

            if (dismiss)  dismiss.addEventListener('click', closeModal);
            if (closeX)   closeX.addEventListener('click', closeModal);
            if (backdrop) backdrop.addEventListener('click', closeModal);

            document.querySelectorAll('.whats-new-action-btn').forEach(function (btn) {
                btn.addEventListener('click', function () { localStorage.setItem(MODAL_KEY, '1'); });
            });

            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
        }
    })();
    </script>
    @endif

    @stack('scripts')
</body>
</html>


