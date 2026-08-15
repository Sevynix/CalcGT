<!DOCTYPE html>
<html lang="id" id="html-root">
<script>if(localStorage.getItem('gtcalc-dark')==='true')document.getElementById('html-root').classList.add('dark');</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Host Panel') - {{ $tournament->name ?? 'Tournament GT' }}</title>
    
    {{-- Icons --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>

    {{-- Compiled CSS + JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js"></script>

    <script>
        window.showAlert = function(msg) {
            if (window.Alpine && window.Alpine.store('modal')) {
                window.Alpine.store('modal').alert(msg);
            } else {
                window.alert(msg);
            }
        };

        document.addEventListener('alpine:init', () => {
            Alpine.store('modal', {
                isOpen: false,
                message: '',
                onConfirm: null,
                title: 'Konfirmasi',
                confirmText: 'Ya',
                type: 'danger',
                showCancel: true,

                show(message, callback, title = 'Konfirmasi', confirmText = 'Ya, Hapus', type = 'danger') {
                    this.message = message;
                    this.onConfirm = callback;
                    this.title = title;
                    this.confirmText = confirmText;
                    this.type = type;
                    this.showCancel = true;
                    this.isOpen = true;
                },
                
                alert(message, title = 'Peringatan', type = 'warning') {
                    this.message = message;
                    this.onConfirm = null;
                    this.title = title;
                    this.confirmText = 'OK';
                    this.type = type;
                    this.showCancel = false;
                    this.isOpen = true;
                },

                confirm() {
                    if (this.onConfirm) this.onConfirm();
                    this.isOpen = false;
                },

                cancel() {
                    this.isOpen = false;
                }
            });
        });
    </script>
    
    @stack('styles')
</head>
<body class="min-h-screen text-slate-800 dark:text-slate-200 font-sans antialiased"
      x-data="{ darkMode: localStorage.getItem('gtcalc-dark') === 'true' }"
      x-init="$watch('darkMode', v => { localStorage.setItem('gtcalc-dark', v); if(document.startViewTransition) { document.startViewTransition(() => document.getElementById('html-root').classList.toggle('dark', v)); } else { document.getElementById('html-root').classList.toggle('dark', v); } })">

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-50 h-16 bg-white/80 dark:bg-[#151b2b]/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80 shadow-sm flex items-center justify-between px-6 transition-all">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/items/guestbook.png') }}" alt="" class="w-6 h-6 object-contain">
            <span class="font-extrabold text-slate-800 dark:text-slate-100 hidden sm:block tracking-tight text-lg">
                {{ $tournament->name ?? 'Host Panel' }}
            </span>
        </div>
        
        <div class="flex items-center gap-4">

            
            <a href="{{ route('host.timeline') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 bg-slate-100 dark:bg-slate-700/50 hover:bg-brand-50 dark:hover:bg-brand-900/30 rounded-lg transition-colors border border-transparent dark:border-slate-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Timeline
            </a>
            
            <form method="POST" action="{{ route('host.logout') }}" class="inline">
                @csrf
                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors border border-transparent dark:border-red-900/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
            
            <!-- Dark Mode Toggle -->
            <button @click="darkMode = !darkMode" type="button" class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-slate-200 dark:bg-brand-500 shadow-inner">
                <span aria-hidden="true" class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="darkMode ? 'translate-x-5' : 'translate-x-0'">
                    <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" :class="darkMode ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </span>
                    <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" :class="darkMode ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'">
                        <svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/></svg>
                    </span>
                </span>
            </button>
        </div>
    </nav>

    <!-- Toast Notifications -->
    <div class="fixed left-0 right-0 z-[9999] flex flex-col items-center gap-3 pointer-events-none" style="top: 90px;">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                 class="pointer-events-auto flex items-center gap-3 bg-white/90 dark:bg-slate-800/95 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/60 shadow-2xl px-5 py-3 rounded-2xl text-[13px] font-bold text-slate-700 dark:text-slate-200 tracking-wide">
                <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                 class="pointer-events-auto flex items-center gap-3 bg-white/90 dark:bg-slate-800/95 backdrop-blur-md border border-slate-200/60 dark:border-slate-700/60 shadow-2xl px-5 py-3 rounded-2xl text-[13px] font-bold text-slate-700 dark:text-slate-200 tracking-wide">
                <div class="w-6 h-6 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto p-6 md:p-8">

        @yield('content')
    </main>

    <!-- ======================== -->
    <!-- GLOBAL CONFIRM MODAL     -->
    <!-- ======================== -->
    <div
        x-data
        x-show="$store.modal.isOpen"
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
            class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center gap-5 border border-slate-200 dark:border-slate-700"
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
                :class="{
                    'bg-amber-50 dark:bg-amber-900/30': $store.modal.type === 'table' || $store.modal.type === 'warning',
                    'bg-blue-50 dark:bg-blue-900/40': $store.modal.type === 'info' || $store.modal.type === 'primary',
                    'bg-red-50 dark:bg-red-900/30': !['table', 'warning', 'info', 'primary'].includes($store.modal.type)
                }"
            >
                <svg x-show="$store.modal.type === 'warning' || $store.modal.type === 'table' || !['table', 'warning', 'info', 'primary'].includes($store.modal.type)" class="w-8 h-8" :class="$store.modal.type === 'table' || $store.modal.type === 'warning' ? 'text-amber-500' : 'text-red-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <svg x-show="$store.modal.type === 'info'" class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg x-show="$store.modal.type === 'primary'" class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Text -->
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 mb-1" x-text="$store.modal.title"></h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium" x-text="$store.modal.message"></p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 w-full mt-1">
                <button
                    x-show="$store.modal.showCancel"
                    @click="$store.modal.cancel()"
                    class="flex-1 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm cursor-pointer"
                >Batal</button>
                <button
                    @click="$store.modal.confirm()"
                    class="flex-1 font-bold px-4 py-3 rounded-2xl transition active:scale-95 text-sm text-white shadow-md cursor-pointer"
                    :class="{
                        'bg-amber-500 hover:bg-amber-600': $store.modal.type === 'warning' || $store.modal.type === 'table',
                        'bg-blue-600 hover:bg-blue-700': $store.modal.type === 'info' || $store.modal.type === 'primary',
                        'bg-red-500 hover:bg-red-600': !['table', 'warning', 'info', 'primary'].includes($store.modal.type)
                    }"
                    x-text="$store.modal.confirmText"
                ></button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

