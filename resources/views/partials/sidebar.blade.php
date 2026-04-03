    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-20'" class="bg-white border-r border-slate-200 hidden md:flex flex-col h-screen sticky top-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-30 shrink-0 transition-all duration-300">
        <!-- Logo Area -->
        <div class="h-16 flex border-b border-slate-100 shrink-0 transition-all duration-300 items-center overflow-hidden" :class="sidebarOpen ? 'justify-start px-6' : 'justify-center px-0'">
            <a href="/" class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2.5 group whitespace-nowrap">
                <div class="bg-gradient-to-tr from-brand-400 to-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-md shrink-0">GT</div> 
                <span x-show="sidebarOpen" x-transition.opacity.duration.300ms>Help<span class="text-brand-500">.</span></span>
            </a>
        </div>
        
        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="flex-1 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-scrollbar" :class="sidebarOpen ? 'px-4' : 'px-2'">
            <a href="{{ route('dashboard') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('dashboard') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/items/guestbook.webp') }}" alt="Guestbook" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Dashboard</span>
            </a>
            
            <div class="pt-6 pb-2" x-show="sidebarOpen">
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest truncate">Calculators</p>
            </div>
            <div class="pt-6 pb-2 flex justify-center" x-show="!sidebarOpen">
                <div class="w-6 border-t-[3px] rounded-full border-slate-200"></div>
            </div>
            
            <a href="{{ route('surgery-tools') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('surgery-tools') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/surgery/surg_crate.webp') }}" alt="Surg Crate" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>MSurg Tools</span>
            </a>

            <a href="{{ route('autoclave') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('autoclave') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/surgery/autoclave.webp') }}" alt="Autoclave" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Autoclave</span>
            </a>

            <a href="{{ route('gala-tools') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('gala-tools') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/startopia/startopia_crate.webp') }}" alt="Startopia" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Galactic Tools</span>
            </a>

            <a href="{{ route('fishin-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('fishin-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/fishing/fishing_crate.webp') }}" alt="Fishing Pack" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Fishing Pack</span>
            </a>

            <a href="{{ route('tackle-box') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('tackle-box') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/fishing/fishing_pack/tackle_box.webp') }}" alt="Tackle Box" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Tackle Box</span>
            </a>

            <a href="{{ route('firefighter-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('firefighter-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/firefighter/firehouse.webp') }}" alt="Firehouse" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Firefighter Pack</span>
            </a>

            <a href="{{ route('city-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('city-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/city/atm.webp') }}" alt="City Pack" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>City Pack</span>
            </a>

            <a href="{{ route('farm-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('farm-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/farm/tractor.webp') }}" alt="Farm Pack" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Farm Pack</span>
            </a>

            <a href="{{ route('paleontologist-kit') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('paleontologist-kit') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/paleontologist/rock_hammer.webp') }}" alt="Rock Hammer" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Paleontologist Kit</span>
            </a>

            <a href="{{ route('science-kit') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('science-kit') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/science/chem/r.webp') }}" alt="Chemical R" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Science Kit</span>
            </a>

            <a href="{{ route('boo-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('boo-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/boo/jar.webp') }}" alt="Ghost Jar" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>B.O.O. Pack</span>
            </a>

            <a href="{{ route('crime-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('crime-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/crime/crime_crate.webp') }}" alt="Crime Wave" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Crime Pack</span>
            </a>

            <a href="{{ route('painter-pack') }}" class="flex items-center rounded-xl transition duration-200 group relative overflow-hidden whitespace-nowrap {{ request()->routeIs('painter-pack') ? 'bg-[#f0f9ff] text-slate-800 font-bold shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700 font-medium' }}" :class="sidebarOpen ? 'gap-3 px-3 py-2.5' : 'justify-center p-3'">
                <span class="opacity-90 group-hover:opacity-100 transition-opacity shrink-0 flex items-center justify-center w-5 h-5">
                    <img src="{{ asset('images/paint/red.webp') }}" alt="Painter Pack" class="w-full h-full object-contain">
                </span> 
                <span class="text-[14.5px]" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Painter Pack</span>
            </a>
        </nav>
        
        <!-- Sidebar Footer Component -->
        <div class="border-t border-slate-100 shrink-0 overflow-hidden" :class="sidebarOpen ? 'p-4' : 'p-2'">
            <div class="bg-slate-50 rounded-xl text-center flex flex-col items-center justify-center" :class="sidebarOpen ? 'p-4' : 'p-2 py-4'">
                <p x-show="sidebarOpen" class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1.5 truncate w-full">Growtopia Help</p>
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
                    // Slight delay to ensure Alpine has finished rendering classes that affect layout
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
