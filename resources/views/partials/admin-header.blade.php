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
                <!-- Public Site Link -->
                <a href="{{ route('dashboard') }}" target="_blank" class="flex items-center gap-1.5 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 px-4 py-1.5 rounded-lg transition-all duration-200 shadow-sm shadow-brand-500/20 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span class="hidden sm:inline">Public Site</span>
                </a>

                <!-- Logout Button -->
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </header>
