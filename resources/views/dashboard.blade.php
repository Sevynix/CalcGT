@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    <!-- Premium Featured Promotions -->
    <div x-data="{
            activeSlide: 0,
            interval: null,
            slides: [
                { 
                    title: 'BBM Provider Tools', 
                    desc: 'Maximize your Building Blocks Machine profits with precise tracking.',
                    tag: 'Top Tool',
                    mainImage: '{{ asset('images/promos/promo_bbm.png') }}', 
                },
                { 
                    title: 'Surgery Simulator', 
                    desc: 'Compute MSurg packs, comprehensive cost breakdowns, and surgical margins.',
                    tag: 'Recommended',
                    mainImage: '{{ asset('images/promos/promo_surgery.png') }}', 
                },
                { 
                    title: 'Galactic Base Metrics', 
                    desc: 'Explore the Startopia Nanoforge simulations and optimize your economy.',
                    tag: 'New Release',
                    mainImage: '{{ asset('images/promos/promo_galactic.png') }}', 
                }
            ],
            init() {
                this.startInterval();
            },
            startInterval() {
                this.interval = setInterval(() => {
                    this.next();
                }, 5000);
            },
            stopInterval() {
                clearInterval(this.interval);
            },
            resetTimer() {
                this.stopInterval();
                this.startInterval();
            },
            next() {
                this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
            },
            prev() {
                this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1;
            }
        }" 
        class="w-full relative mb-12">
        
        <!-- Section Title -->
        <div class="mb-4 px-2">
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight uppercase">Featured & Recommended</h2>
        </div>

        <div class="relative w-full h-[300px] md:h-[400px] rounded-[32px] overflow-hidden shadow-glass border border-slate-200 bg-white group">
            
            <!-- Left Arrow -->
            <button @click="prev(); resetTimer()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/30 hover:bg-white/70 backdrop-blur-md rounded-full shadow-lg transition-all flex items-center justify-center text-slate-800 opacity-0 group-hover:opacity-100">
                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </button>

            <!-- Slides -->
            <div class="w-full h-full relative">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-500 absolute top-0 left-0"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 -translate-x-4"
                         class="absolute inset-0 w-full h-full pointer-events-none select-none">
                        
                        <!-- Background Image -->
                        <img :src="slide.mainImage" class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none" alt="Promotion" draggable="false">
                        
                    </div>
                </template>
            </div>

            <!-- Right Arrow -->
            <button @click="next(); resetTimer()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/30 hover:bg-white/70 backdrop-blur-md rounded-full shadow-lg transition-all flex items-center justify-center text-slate-800 opacity-0 group-hover:opacity-100">
                <svg class="w-6 h-6 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            </button>
            
            <!-- Indicators -->
            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20 bg-white/40 backdrop-blur-md px-3 py-2 rounded-full border border-white/50 shadow-sm pointer-events-auto">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index; resetTimer()" 
                            class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'bg-brand-500 w-6' : 'bg-slate-400 hover:bg-brand-400'"></button>
                </template>
            </div>

        </div>
    </div>

    <!-- Category Navigation & Tools Grid -->
    <div x-data="{ currentCategory: 'all' }">
        
        <!-- Modern Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-hide text-sm font-black uppercase tracking-wider">
            <button @click="currentCategory = 'all'" :class="currentCategory === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-500 hover:bg-slate-100'" class="px-5 py-2.5 rounded-full transition-colors whitespace-nowrap shadow-sm border border-slate-200">
                All Tools
            </button>
            <button @click="currentCategory = 'providers'" :class="currentCategory === 'providers' ? 'bg-brand-500 text-white' : 'bg-white text-slate-500 hover:bg-slate-100'" class="px-5 py-2.5 rounded-full transition-colors whitespace-nowrap shadow-sm border border-slate-200">
                Providers & Packs
            </button>
            <button @click="currentCategory = 'simulators'" :class="currentCategory === 'simulators' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-500 hover:bg-slate-100'" class="px-5 py-2.5 rounded-full transition-colors whitespace-nowrap shadow-sm border border-slate-200">
                Simulators
            </button>
            <button @click="currentCategory = 'player'" :class="currentCategory === 'player' ? 'bg-purple-500 text-white' : 'bg-white text-slate-500 hover:bg-slate-100'" class="px-5 py-2.5 rounded-full transition-colors whitespace-nowrap shadow-sm border border-slate-200">
                Player & Guild
            </button>
        </div>

        <!-- Unified Tools Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- BBM Provider -->
            <a href="{{ route('bbm-provider') }}" x-show="currentCategory === 'all' || currentCategory === 'providers'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-brand-50 rounded-2xl flex items-center justify-center border border-brand-100 group-hover:bg-brand-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🧱</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-brand-600 tracking-tight leading-tight mb-1">BBM Provider</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Provider</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Track Building Blocks Machine item returns and calculate precise breakdowns for your market profits.</p>
                <div class="mt-auto flex items-center justify-between text-brand-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Surgery Simulator -->
            <a href="{{ route('surgery-tools') }}" x-show="currentCategory === 'all' || currentCategory === 'simulators'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-100 group-hover:bg-emerald-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🥼</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-emerald-600 tracking-tight leading-tight mb-1">Surgery Tools</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Simulator</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Compute MSurg packs, comprehensive cost breakdowns, and surgical profit margins instantly.</p>
                <div class="mt-auto flex items-center justify-between text-emerald-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Galactic Base -->
            <a href="{{ route('gala-tools') }}" x-show="currentCategory === 'all' || currentCategory === 'simulators'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center border border-rose-100 group-hover:bg-rose-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🌌</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-rose-600 tracking-tight leading-tight mb-1">Galactic Tools</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Simulator</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Explore the Startopia Nanoforge simulations and optimize your intergalactic economy tools.</p>
                <div class="mt-auto flex items-center justify-between text-rose-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- SSP Pack -->
            <a href="{{ route('ssp-pack') }}" x-show="currentCategory === 'all' || currentCategory === 'providers'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center border border-green-100 group-hover:bg-green-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🌱</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-green-600 tracking-tight leading-tight mb-1">SSP Pack</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Provider</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Calculate costs and returns for the Small Seed Pack to determine true profit margins.</p>
                <div class="mt-auto flex items-center justify-between text-green-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Crime Pack -->
            <a href="{{ route('crime-pack') }}" x-show="currentCategory === 'all' || currentCategory === 'providers'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center border border-red-100 group-hover:bg-red-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🦹</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-red-600 tracking-tight leading-tight mb-1">Crime Pack</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Provider</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Analyze crime pack openings, card drop rates, and overall net worth gains.</p>
                <div class="mt-auto flex items-center justify-between text-red-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Roles Calculator -->
            <a href="{{ route('roles-calculator') }}" x-show="currentCategory === 'all' || currentCategory === 'player'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center border border-purple-100 group-hover:bg-purple-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🎭</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-purple-600 tracking-tight leading-tight mb-1">Roles Calculator</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Player Base</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Plan your player role progressions, XP requirements, and perk unlock milestones.</p>
                <div class="mt-auto flex items-center justify-between text-purple-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Guild Calculator -->
            <a href="{{ route('guild-level') }}" x-show="currentCategory === 'all' || currentCategory === 'player'" 
               class="group relative bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="flex items-start gap-5 mb-4">
                    <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center border border-orange-100 group-hover:bg-orange-500 transition-colors shrink-0">
                        <span class="text-3xl group-hover:scale-110 transition-transform">🛡️</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 group-hover:text-orange-600 tracking-tight leading-tight mb-1">Guild Level</h3>
                        <span class="inline-block bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-widest px-2 py-0.5 rounded">Guild Hub</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Estimate guild experience points required for levels and manage contribution goals.</p>
                <div class="mt-auto flex items-center justify-between text-orange-500 font-bold text-sm">
                    <span>Launch Tool</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

        </div>

    </div>

</div>
@endsection

