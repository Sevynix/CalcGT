@extends('layouts.app')
@section('title', 'Dashboard')
@section('meta_description', 'Growtopia Calculator dashboard — browse all calculators and tools for MSurg, SSP, RSP, farming packs, player levels, guild tools and more.')

@section('preload')
@php
    $preloadBanner = $banners->where('position', 1)->first();
    $preloadSrc = $preloadBanner && $preloadBanner->image_path
        ? asset($preloadBanner->image_path)
        : asset('images/promos/banner.webp');
@endphp
<link rel="preload" as="image" href="{{ $preloadSrc }}" fetchpriority="high">
@endsection

@section('content')
<script>
function promoCarousel() {
    return {
        activeSlide: 0,
        interval: null,
        init() { this.startInterval(); },
        startInterval() { this.interval = setInterval(() => { this.next(); }, 5000); },
        stopInterval() { clearInterval(this.interval); },
        resetTimer() { this.stopInterval(); this.startInterval(); },
        next() { this.activeSlide = this.activeSlide === 2 ? 0 : this.activeSlide + 1; },
        prev() { this.activeSlide = this.activeSlide === 0 ? 2 : this.activeSlide - 1; },
        touchStartX: 0,
        handleTouchStart(e) { this.touchStartX = e.changedTouches[0].clientX; },
        handleTouchEnd(e) {
            const delta = e.changedTouches[0].clientX - this.touchStartX;
            if (Math.abs(delta) < 50) return;
            delta < 0 ? this.next() : this.prev();
            this.resetTimer();
        }
    };
}
</script>
<div class="max-w-6xl mx-auto space-y-10">

    <!-- Premium Featured Promotions (Grid Layout) -->
    <div class="w-full mb-12">
        <div class="mb-4 px-2">
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight uppercase">Featured &amp; Recommended</h1>
        </div>
        
        @php
            $slot1 = $banners->where('position', 1)->first();
            $slot2 = $banners->where('position', 2)->first();
            $slot3 = $banners->where('position', 3)->first();
            
            $defaultImage = asset('images/promos/banner.webp');
            $defaultLink = 'https://discord.gg/QuFkQyAnJ8';
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" style="min-height: 320px;">
            {{-- LEFT: Large hero banner (Spans 2 columns) --}}
            @php
                $s1Image = $slot1 && $slot1->image_path ? asset($slot1->image_path) : $defaultImage;
                $s1Link = $slot1 ? $slot1->link_url : $defaultLink;
            @endphp
            <a @if($s1Link) href="{{ $s1Link }}" target="_blank" rel="noopener noreferrer" @endif class="md:col-span-2 relative rounded-[32px] overflow-hidden shadow-glass border border-slate-200 bg-white group w-full block aspect-[3/2]">
                <img src="{{ $s1Image }}" loading="eager" fetchpriority="high" alt="Slot 1 Banner" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02] block" draggable="false">
            </a>

            {{-- RIGHT: Two smaller stacked banners (Span 1 column) --}}
            <div class="md:col-span-1 flex flex-row md:flex-col items-start md:items-stretch gap-3 md:gap-4 w-full h-full">
                {{-- Slot 2 (Top small) --}}
                @php
                    $s2Image = $slot2 && $slot2->image_path ? asset($slot2->image_path) : $defaultImage;
                    $s2Link = $slot2 ? $slot2->link_url : $defaultLink;
                @endphp
                <a @if($s2Link) href="{{ $s2Link }}" target="_blank" rel="noopener noreferrer" @endif class="relative rounded-[32px] overflow-hidden shadow-glass border border-slate-200 bg-white group flex-1 w-full flex items-center justify-center aspect-[3/2] block">
                    <img src="{{ $s2Image }}" loading="lazy" alt="Slot 2 Banner" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02] block" draggable="false">
                </a>

                {{-- Slot 3 (Bottom small) --}}
                @php
                    $s3Image = $slot3 && $slot3->image_path ? asset($slot3->image_path) : $defaultImage;
                    $s3Link = $slot3 ? $slot3->link_url : $defaultLink;
                @endphp
                <a @if($s3Link) href="{{ $s3Link }}" target="_blank" rel="noopener noreferrer" @endif class="relative rounded-[32px] overflow-hidden shadow-glass border border-slate-200 bg-white group flex-1 w-full flex items-center justify-center aspect-[3/2] block">
                    <img src="{{ $s3Image }}" loading="lazy" alt="Slot 3 Banner" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02] block" draggable="false">
                </a>
            </div>
        </div>

        {{-- Promote Link --}}
        <div class="mt-3 flex justify-start px-1">
            <a href="https://discord.gg/QuFkQyAnJ8" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-brand-50 hover:bg-brand-500 text-brand-600 hover:text-white rounded-full text-[12px] font-black uppercase tracking-wider transition-all duration-300 shadow-sm border border-brand-200 hover:border-brand-500">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" /></svg>
                Promote banner here!
            </a>
        </div>
    </div>
    <!-- Category Navigation & Tools Grid -->
    <div x-data="{ currentCategory: 'popular' }">
        
        <!-- Modern Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-hide text-sm font-black uppercase tracking-wider">
            <button @click="currentCategory = 'popular'" :class="currentCategory === 'popular' ? 'bg-slate-800 dark:bg-brand-600 text-white border-transparent' : 'bg-white dark:bg-[#151b2b] text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#1c2438] hover:text-slate-700 dark:hover:text-slate-200 border-slate-200 dark:border-[#2a3449]'" class="px-5 py-2.5 rounded-full transition-all duration-300 whitespace-nowrap shadow-sm border cursor-pointer active:scale-95">
                Popular Search
            </button>
            <button @click="currentCategory = 'provider'" :class="currentCategory === 'provider' ? 'bg-slate-800 dark:bg-brand-600 text-white border-transparent' : 'bg-white dark:bg-[#151b2b] text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#1c2438] hover:text-slate-700 dark:hover:text-slate-200 border-slate-200 dark:border-[#2a3449]'" class="px-5 py-2.5 rounded-full transition-all duration-300 whitespace-nowrap shadow-sm border cursor-pointer active:scale-95">
                Provider
            </button>
            <button @click="currentCategory = 'player'" :class="currentCategory === 'player' ? 'bg-slate-800 dark:bg-brand-600 text-white border-transparent' : 'bg-white dark:bg-[#151b2b] text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#1c2438] hover:text-slate-700 dark:hover:text-slate-200 border-slate-200 dark:border-[#2a3449]'" class="px-5 py-2.5 rounded-full transition-all duration-300 whitespace-nowrap shadow-sm border cursor-pointer active:scale-95">
                Player Tools
            </button>
            <button @click="currentCategory = 'guild'" :class="currentCategory === 'guild' ? 'bg-slate-800 dark:bg-brand-600 text-white border-transparent' : 'bg-white dark:bg-[#151b2b] text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#1c2438] hover:text-slate-700 dark:hover:text-slate-200 border-slate-200 dark:border-[#2a3449]'" class="px-5 py-2.5 rounded-full transition-all duration-300 whitespace-nowrap shadow-sm border cursor-pointer active:scale-95">
                Guild Tools
            </button>
            <button @click="currentCategory = 'store'" :class="currentCategory === 'store' ? 'bg-slate-800 dark:bg-brand-600 text-white border-transparent' : 'bg-white dark:bg-[#151b2b] text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#1c2438] hover:text-slate-700 dark:hover:text-slate-200 border-slate-200 dark:border-[#2a3449]'" class="px-5 py-2.5 rounded-full transition-all duration-300 whitespace-nowrap shadow-sm border cursor-pointer active:scale-95">
                Store
            </button>
        </div>

        <!-- Unified Tools Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            <!-- MSurg Tools -->
            <a href="{{ route('surgery-tools') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/surgery/surg_crate.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/surgery/surg_crate.png') }}" alt="MSurg Tools" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">MSurg Tools</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Compute MSurg packs, cost breakdowns, and surgical profit margins instantly.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Startopia Tools -->
            <a href="{{ route('gala-tools') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/startopia/crate.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/startopia/crate.png') }}" alt="Startopia Tools" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Startopia Tools</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Explore Startopia simulations and optimize your intergalactic economy.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Crime Pack -->
            <a href="{{ route('crime-pack') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/crime/crate.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/crime/crate.png') }}" alt="Crime Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Crime Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Analyze crime pack openings and calculate overall net worth gains per run.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- SSP -->
            <a href="{{ route('ssp-pack') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/ssp/ssp.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/ssp/ssp.png') }}" alt="SSP" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">SSP</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Calculate costs and returns for the Small Seed Pack to determine true profit margins.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- RSP -->
            <a href="{{ route('rsp-pack') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/rsp/2010.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/rsp/2010.png') }}" alt="RSP" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">RSP</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Track and calculate costs and returns for the Rare Seed Pack.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Autoclave -->
            <a href="{{ route('autoclave') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/surgery/autoclave.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/surgery/autoclave.png') }}" alt="Autoclave" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Autoclave</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Calculate requirements and simulate autoclave operations for surgery.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Nanoforge -->
            <a href="{{ route('nanoforge') }}" x-show="currentCategory === 'popular'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/startopia/nanoforge.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/startopia/nanoforge.png') }}" alt="Nanoforge" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Popular Search</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Nanoforge</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Plan your Nanoforge upgrades and track material requirements.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- BBM -->
            <a href="{{ route('bbm-provider') }}" x-show="currentCategory === 'provider'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/bbm/bbm.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/bbm/bbm.png') }}" alt="BBM" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Provider</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">BBM</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Track Building Blocks Machine item returns and calculate precise profit breakdowns.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Science Station -->
            <a href="{{ route('science-provider') }}" x-show="currentCategory === 'provider'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/science/science_station.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/science/science_station.png') }}" alt="Science Station" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Provider</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Science Station</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Monitor Science Station outputs and track your chemical harvesting profits.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Tackle Box -->
            <a href="{{ route('tackle-box') }}" x-show="currentCategory === 'provider'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/fishing/fishing_pack/tackle.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/fishing/fishing_pack/tackle.png') }}" alt="Tackle Box" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Provider</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Tackle Box</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Manage your Tackle Box returns and optimize your fishing profits.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Player Level -->
            <a href="{{ route('level-calculator') }}" x-show="currentCategory === 'player'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/items/exp_potion.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/items/exp_potion.png') }}" alt="Player Level" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Player Tools</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Player Level</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Calculate XP requirements to reach your desired player level.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Roles Calculator -->
            <a href="{{ route('roles-calculator') }}" x-show="currentCategory === 'player'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/roles/farmer.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/roles/farmer.png') }}" alt="Roles Calculator" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Player Tools</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Roles Calculator</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Plan role progressions, XP requirements, and perk unlock milestones.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Backpack Upgrades -->
            <a href="{{ route('backpack-calculator') }}" x-show="currentCategory === 'player'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/items/backpack_upgrade.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/items/backpack_upgrade.png') }}" alt="Backpack Upgrades" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Player Tools</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Backpack Upgrades</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Track backpack upgrade costs and inventory expansion milestones.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Guild Level -->
            <a href="{{ route('guild-level') }}" x-show="currentCategory === 'guild'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/lock/guild_lock.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/lock/guild_lock.png') }}" alt="Guild Level" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Guild Tools</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Guild Level</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Estimate guild XP requirements per level and manage contribution goals.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Fishing Pack -->
            <a href="{{ route('fishin-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/fishing/crate.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/fishing/crate.png') }}" alt="Fishing Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Fishing Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Analyze fishing pack openings and calculate returns.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Painter Pack -->
            <a href="{{ route('painter-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/paint/red.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/paint/red.png') }}" alt="Painter Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Painter Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Track painter pack openings and optimize profit margins.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- City Pack -->
            <a href="{{ route('city-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/city/atm.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/city/atm.png') }}" alt="City Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">City Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Calculate costs and expected items from City Pack openings.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Firefighter Pack -->
            <a href="{{ route('firefighter-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/firefighter/house.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/firefighter/house.png') }}" alt="Firefighter Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Firefighter Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Simulate Firefighter pack openings and track your drops.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Farm Pack -->
            <a href="{{ route('farm-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/farm/tractor.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/farm/tractor.png') }}" alt="Farm Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Farm Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Analyze farm pack returns and optimize your farming setup.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Paleontologist Kit -->
            <a href="{{ route('paleontologist-kit') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/paleontologist/hammer.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/paleontologist/hammer.png') }}" alt="Paleontologist Kit" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Paleontologist Kit</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Calculate costs and expected returns from Paleontologist Kits.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- Science Kit -->
            <a href="{{ route('science-kit') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/science/chem/r.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/science/chem/r.png') }}" alt="Science Kit" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Science Kit</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Track your Science Kit openings and calculate expected chemical yields.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            <!-- B.O.O. Pack -->
            <a href="{{ route('boo-pack') }}" x-show="currentCategory === 'store'" style="display:block"
               class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5">
                <!-- Blurry Logo -->
                <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none">
                    <img src="{{ asset('images/boo/jar.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 gap-2">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                            <img src="{{ asset('images/boo/jar.png') }}" alt="B.O.O. Pack" class="h-8 w-8 object-contain">
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">Store</span>
                    </div>
                    
                    <div class="mt-3">
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">B.O.O. Pack</h2>
                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">Analyze B.O.O. Pack drops and track your spooky returns.</p>
                    </div>
                    
                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto">
                        <span>Open Tool</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>

        </div>

    </div>

</div>

    {{-- ============================================================ --}}
    {{-- SEO: Crawlable links for all tool pages                      --}}
    {{-- Hidden from users (sr-only), always visible to Googlebot     --}}
    {{-- Fixes "Duplicate, Google chose different canonical" issue     --}}
    {{-- caused by Alpine.js x-show hiding non-popular category cards --}}
    {{-- ============================================================ --}}
    <nav aria-label="All tools index" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">
        {{-- Provider Tools --}}
        <a href="{{ route('bbm-provider') }}">BBM Provider Calculator — Building Blocks Machine profit tracker for Growtopia</a>
        <a href="{{ route('science-provider') }}">Science Station Provider Calculator — track chemical harvesting profits in Growtopia</a>
        <a href="{{ route('tackle-box') }}">Tackle Box Provider Calculator — fishing returns and profit margins in Growtopia</a>

        {{-- Player Tools --}}
        <a href="{{ route('level-calculator') }}">Player Level Calculator — XP requirements to reach your desired Growtopia level</a>
        <a href="{{ route('roles-calculator') }}">Roles Calculator — plan role progressions and XP milestones in Growtopia</a>
        <a href="{{ route('backpack-calculator') }}">Backpack Upgrade Calculator — inventory expansion costs for Growtopia</a>

        {{-- Guild Tools --}}
        <a href="{{ route('guild-level') }}">Guild Level Calculator — estimate guild XP requirements per level in Growtopia</a>

        {{-- Store / Pack Tools --}}
        <a href="{{ route('fishin-pack') }}">Fishing Pack Calculator — analyze pack openings and calculate Growtopia returns</a>
        <a href="{{ route('painter-pack') }}">Painter Pack Calculator — track painter pack openings and profit in Growtopia</a>
        <a href="{{ route('city-pack') }}">City Pack Calculator — costs and expected items from City Pack openings</a>
        <a href="{{ route('farm-pack') }}">Farm Pack Calculator — Growtopia farm pack profit and return tracker</a>
        <a href="{{ route('firefighter-pack') }}">Firefighter Pack Calculator — firefighter pack returns for Growtopia</a>
        <a href="{{ route('boo-pack') }}">B.O.O. Pack Calculator — analyze B.O.O. Pack drops in Growtopia</a>
        <a href="{{ route('crime-pack') }}">Crime Pack Calculator — analyze crime pack openings in Growtopia</a>
        <a href="{{ route('ssp-pack') }}">SSP Calculator — Small Seed Pack profit margins for Growtopia</a>
        <a href="{{ route('rsp-pack') }}">RSP Calculator — Rare Seed Pack costs and returns for Growtopia</a>
        <a href="{{ route('science-kit') }}">Science Kit Calculator — Growtopia science kit tools</a>
        <a href="{{ route('paleontologist-kit') }}">Paleontologist Kit Calculator — Growtopia paleontologist kit tracker</a>
    </nav>

@endsection
