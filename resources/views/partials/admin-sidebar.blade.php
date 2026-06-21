    <!-- ===================== -->
    <!-- MOBILE NAVIGATION DRAWER -->
    <!-- ===================== -->

    <!-- Backdrop -->
    <div
        x-show="mobileNavOpen"
        @click="mobileNavOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden"
        style="display:none;"
    ></div>

    <!-- Drawer Panel -->
    <div
        x-show="mobileNavOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 h-full w-72 max-w-[85vw] bg-white z-50 flex flex-col shadow-2xl md:hidden transform"
        style="display:none;"
    >
        <!-- Drawer Header -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 shrink-0">
            <a href="/" class="flex items-center">
                <img src="{{ asset('images/logofix.png') }}" alt="Logo" class="h-10 w-auto object-contain">
            </a>
            <button @click="mobileNavOpen = false" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" aria-label="Close Navigation">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Drawer Nav Links -->
        <nav class="flex-1 py-4 space-y-1 overflow-y-auto px-3">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard', 'label' => 'Admin Dashboard', 'img' => 'images/items/guestbook.png', 'alt' => 'Admin'],
                    ['route' => 'admin.banners.index', 'label' => 'Banners', 'img' => 'images/items/bulletin.png', 'alt' => 'Banners'],
                    ['route' => 'admin.feedback.index', 'label' => 'Feedback', 'img' => 'images/items/letter.png', 'alt' => 'Feedback'],
                    ['route' => 'admin.patch-notes.index', 'label' => 'Patch Notes', 'img' => 'images/items/guestbook.png', 'alt' => 'Updates'],
                    ['route' => 'admin.community-posts.index', 'label' => 'Community Topics', 'img' => 'images/items/megaphone.png', 'alt' => 'Topics'],
                    ['route' => 'admin.community-opinions.index', 'label' => 'User Opinions', 'img' => 'images/items/pencil.png', 'alt' => 'Opinions'],
                    ['route' => 'admin.settings.whats-new', 'label' => 'Modal Settings', 'img' => 'images/items/Menu_button.png', 'alt' => 'Settings'],
                ];
                $activeClass = 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm';
                $inactiveClass = 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium';
            @endphp
            <div class="pt-5 pb-2"><p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Admin Panel</p></div>
            @foreach($adminNav as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" @click="mobileNavOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200 whitespace-nowrap {{ $isActive ? $activeClass : $inactiveClass }}">
                    <span class="shrink-0 flex items-center justify-center w-5 h-5">
                        <img loading="lazy" src="{{ asset($item['img']) }}" alt="{{ $item['alt'] }}" class="w-full h-full object-contain">
                    </span>
                    <span class="text-[14.5px]">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Drawer Footer -->
        <div class="border-t border-slate-100 p-4 shrink-0">
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">Growtopia Calculator</p>
                <div class="flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Made by: Sevynix</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== -->
    <!-- DESKTOP SIDEBAR       -->
    <!-- ===================== -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-20'" class="bg-white border-r border-slate-200 hidden md:flex flex-col h-screen sticky top-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-30 shrink-0 transition-all duration-300">
        <!-- Logo Area -->
        <div class="h-16 flex border-b border-slate-100 shrink-0 transition-all duration-300 items-center overflow-hidden w-full" :class="sidebarOpen ? 'justify-start px-6' : 'justify-center'">
            <a href="/" class="flex items-center group whitespace-nowrap">
                <img src="{{ asset('images/logofix.png') }}" alt="Logo" class="h-10 w-auto object-contain transition-all duration-300" :class="sidebarOpen ? 'max-w-[160px]' : 'max-w-[50px]'">
            </a>
        </div>
        
        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-scrollbar" :class="sidebarOpen ? 'px-4' : 'px-2'">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard', 'label' => 'Admin Dashboard', 'img' => 'images/items/guestbook.png', 'alt' => 'Admin'],
                    ['route' => 'admin.banners.index', 'label' => 'Banners', 'img' => 'images/items/bulletin.png', 'alt' => 'Banners'],
                    ['route' => 'admin.feedback.index', 'label' => 'Feedback', 'img' => 'images/items/letter.png', 'alt' => 'Feedback'],
                    ['route' => 'admin.patch-notes.index', 'label' => 'Patch Notes', 'img' => 'images/items/guestbook.png', 'alt' => 'Updates'],
                    ['route' => 'admin.community-posts.index', 'label' => 'Community Topics', 'img' => 'images/items/megaphone.png', 'alt' => 'Topics'],
                    ['route' => 'admin.community-opinions.index', 'label' => 'User Opinions', 'img' => 'images/items/pencil.png', 'alt' => 'Opinions'],
                    ['route' => 'admin.settings.whats-new', 'label' => 'Modal Settings', 'img' => 'images/items/Menu_button.png', 'alt' => 'Settings'],
                ];
                $activeClass = 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm';
                $inactiveClass = 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium';
            @endphp
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest truncate">Admin Panel</p>
            </div>
            <div class="pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
                <div class="w-6 border-t-[3px] rounded-full border-slate-200"></div>
            </div>
            @foreach($adminNav as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ $isActive ? $activeClass : $inactiveClass }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                    <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                        <img loading="lazy" src="{{ asset($item['img']) }}" alt="{{ $item['alt'] }}" class="w-full h-full object-contain">
                    </span>
                    <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
        
        <!-- Sidebar Footer Component -->
        <div class="border-t border-slate-100 shrink-0 overflow-hidden" :class="sidebarOpen ? 'p-4' : 'p-2'">
            <div class="bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center" :class="sidebarOpen ? 'p-4' : 'p-2 py-4'">
                <p x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5 truncate w-full">Growtopia Calculator</p>
                <div class="flex items-center justify-center text-xs font-semibold text-slate-500" :class="sidebarOpen ? 'gap-1.5' : ''">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0" :class="!sidebarOpen ? 'w-2 h-2' : ''"></span>
                    <span x-show="sidebarOpen">Made by: Sevynix</span>
                </div>
            </div>
        </div>
    </aside>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const nav = document.getElementById('sidebar-nav');
            if (nav) {
                const scrollpos = sessionStorage.getItem('sidebar-scroll');
                if (scrollpos) {
                    setTimeout(() => {
                        nav.scrollTop = parseInt(scrollpos, 10);
                    }, 50);
                }
                
                nav.addEventListener('scroll', () => {
                    sessionStorage.setItem('sidebar-scroll', nav.scrollTop);
                });
            }
        });
    </script>
