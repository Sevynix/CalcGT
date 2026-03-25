        <!-- Top Application Header -->
        <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-20 shrink-0 shadow-sm transition-all">
            <div class="flex items-center gap-4">
                <!-- Sidebar Toggle Buttons -->
                <button class="md:hidden p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex p-2 bg-transparent text-slate-400 hover:text-brand-500 hover:bg-slate-50 rounded-lg transition items-center justify-center" aria-label="Toggle Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <!-- Dynamic Page Title provided by views -->
                <h2 class="text-[17px] font-extrabold text-slate-700 hidden sm:block tracking-tight bg-clip-text">@yield('title', 'Dashboard')</h2>
            </div>
            
            <!-- Header Right Menu -->
            <div class="flex items-center gap-5">
            </div>
        </header>
