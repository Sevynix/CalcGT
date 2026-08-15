<!DOCTYPE html>
<html lang="id" id="html-root">
<script>if(localStorage.getItem('gtcalc-dark')==='true')document.getElementById('html-root').classList.add('dark');</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Login - Tournament GT</title>
    
    {{-- Icons --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-sans antialiased flex items-center justify-center p-4 transition-colors duration-200"
      x-data="{ darkMode: localStorage.getItem('gtcalc-dark') === 'true' }"
      x-init="$watch('darkMode', v => { localStorage.setItem('gtcalc-dark', v); document.getElementById('html-root').classList.toggle('dark', v); })">

    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 pointer-events-none bg-[radial-gradient(ellipse_at_50%_40%,rgba(16,185,129,0.08)_0%,transparent_60%)] dark:bg-[radial-gradient(ellipse_at_50%_40%,rgba(16,185,129,0.15)_0%,transparent_60%)]"></div>

    <!-- Theme Toggle at top right -->
    <div class="absolute top-6 right-6">
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

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-[420px] bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 rounded-3xl p-8 sm:p-10 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_60px_rgba(0,0,0,0.5)]">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 mb-4 border border-emerald-100 dark:border-emerald-800/50">
                <img src="{{ asset('images/items/guestbook.png') }}" alt="" class="w-8 h-8 object-contain">
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-emerald-400 tracking-tight">Host Panel</h1>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1.5">Masuk dengan Host Key dari Admin</p>
        </div>

        @if($errors->any())
            <div class="mb-5 flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('host.authenticate') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Host Key</label>
                <input type="text" name="key" 
                       class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-slate-800 dark:text-slate-100 font-mono text-center tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:focus:ring-emerald-500/20 dark:focus:border-emerald-500 transition-all placeholder-slate-300 dark:placeholder-slate-600" 
                       placeholder="XXXXX-XXXXX" required autocomplete="off" autofocus>
            </div>
            
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-[0_8px_24px_rgba(16,185,129,0.25)] hover:shadow-[0_8px_24px_rgba(16,185,129,0.4)] hover:-translate-y-0.5 active:translate-y-0">
                <img src="{{ asset('images/items/trophy.png') }}" alt="" class="w-5 h-5 object-contain">
                Masuk sebagai Host
            </button>
        </form>
    </div>

</body>
</html>

