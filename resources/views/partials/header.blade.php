        <!-- Top Application Header -->
        <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-20 shrink-0 shadow-sm transition-all">
            <div class="flex items-center gap-4">
                <!-- Sidebar Toggle Buttons -->
                <button @click="mobileNavOpen = true" class="md:hidden p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition" aria-label="Open Navigation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex p-2 bg-transparent text-slate-400 hover:text-brand-500 hover:bg-slate-50 rounded-lg transition items-center justify-center" aria-label="Toggle Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <!-- Dynamic Page Title provided by views -->
                <h2 class="text-[17px] font-extrabold text-slate-700 hidden sm:block tracking-tight bg-clip-text">@yield('title', 'Dashboard')</h2>
            </div>
            
            <!-- Header Right: Quick links -->
            <div class="flex items-center gap-2">
                <!-- Back to Dashboard shortcut (hidden on dashboard itself) -->
                @unless(request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}"
                   class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600 bg-slate-100 hover:bg-brand-50 border border-transparent hover:border-brand-200 px-3 py-1.5 rounded-lg transition-all duration-200"
                   title="Back to Dashboard">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                @endunless
                <!-- Feedback Board -->
                <a href="{{ route('feedback.index') }}" class="flex items-center gap-1.5 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 px-4 py-1.5 rounded-lg transition-all duration-200 shadow-sm shadow-brand-500/20 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    <span class="hidden sm:inline">Feedback</span>
                </a>

                <!-- About Us / Policy Link -->
                <a href="{{ route('about') }}" class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-600 bg-slate-50 border border-slate-200 hover:border-brand-200 hover:bg-brand-50 px-3 py-1.5 rounded-lg transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">About & Policy</span>
                </a>
            </div>
        </header>
