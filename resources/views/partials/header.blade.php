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

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" type="button" 
                    class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-slate-200 dark:bg-brand-500 shadow-inner"
                    :aria-label="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    :title="darkMode ? 'Light Mode' : 'Dark Mode'"
                    role="switch" :aria-checked="darkMode">
                    <span class="sr-only">Toggle dark mode</span>
                    <span aria-hidden="true" 
                        class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        :class="darkMode ? 'translate-x-5' : 'translate-x-0'">
                        <!-- Moon icon for light mode -->
                        <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" :class="darkMode ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </span>
                        <!-- Sun icon for dark mode -->
                        <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" :class="darkMode ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'">
                            <svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
                            </svg>
                        </span>
                    </span>
                </button>

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
                <a href="{{ route('feedback.index') }}" aria-label="Feedback Board" class="flex items-center gap-1.5 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 px-4 py-1.5 rounded-lg transition-all duration-200 shadow-sm shadow-brand-500/20 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    <span class="hidden sm:inline">Feedback</span>
                </a>

                <!-- Community Opinions Link -->
                @php
                    $hasCommunityPosts = \App\Models\CommunityPost::where('is_published', true)->exists();
                @endphp
                @if($hasCommunityPosts)
                    <a id="nav-community-btn" href="{{ route('opinions.index') }}" aria-label="Community Opinions" class="relative flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-600 bg-slate-50 border border-slate-200 hover:border-brand-200 hover:bg-brand-50 px-3 py-1.5 rounded-lg transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span class="hidden sm:inline">Community</span>
                        <!-- Red notification dot -->
                        <span id="community-dot"
                              style="display:none; position:absolute; top:-4px; right:-4px; width:10px; height:10px; background:#ef4444; border-radius:50%; box-shadow:0 0 0 2px #fff;">
                            <span style="position:absolute; inset:0; border-radius:50%; background:#f87171; animation:wn-ping-soft 1.6s ease-in-out infinite;"></span>
                        </span>
                    </a>
                @endif

                <!-- Updates Link -->
                <a id="nav-updates-btn" href="{{ route('updates') }}" aria-label="Updates & Patch Notes" class="relative flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-600 bg-slate-50 border border-slate-200 hover:border-brand-200 hover:bg-brand-50 px-3 py-1.5 rounded-lg transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                    </svg>
                    <span class="hidden sm:inline">Updates</span>
                    <!-- Red notification dot -->
                    <span id="updates-dot"
                          style="display:none; position:absolute; top:-4px; right:-4px; width:10px; height:10px; background:#ef4444; border-radius:50%; box-shadow:0 0 0 2px #fff;">
                        <span style="position:absolute; inset:0; border-radius:50%; background:#f87171; animation:wn-ping-soft 1.6s ease-in-out infinite;"></span>
                    </span>
                </a>

                <!-- About Us / Policy Link -->
                <a href="{{ route('about') }}" aria-label="About & Policy" class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-600 bg-slate-50 border border-slate-200 hover:border-brand-200 hover:bg-brand-50 px-3 py-1.5 rounded-lg transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">About & Policy</span>
                </a>
            </div>
        </header>

        {{-- Notification dot JS (localStorage-driven) --}}
        <script>
        (function () {
            'use strict';
            var UPDATES_KEY   = 'gtcalc_seen_updates';
            var COMMUNITY_KEY = 'gtcalc_seen_community';
            // Clear dots when already on the target page
            var path = window.location.pathname;
            if (path.includes('/updates'))   localStorage.setItem(UPDATES_KEY,   '1');
            if (path.includes('/community') || path.includes('/opinions')) localStorage.setItem(COMMUNITY_KEY, '1');
            // Show dots if not yet visited
            document.addEventListener('DOMContentLoaded', function () {
                if (!localStorage.getItem(UPDATES_KEY)) {
                    var dot = document.getElementById('updates-dot');
                    if (dot) dot.style.display = 'block';
                }
                if (!localStorage.getItem(COMMUNITY_KEY)) {
                    var cdot = document.getElementById('community-dot');
                    if (cdot) cdot.style.display = 'block';
                }
            });
        })();
        </script>
