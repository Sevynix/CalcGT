@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Welcome Hero -->
    <div class="bg-gradient-to-r from-brand-500 to-blue-600 rounded-[32px] p-8 sm:p-10 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Welcome back, Pro User! 👋</h1>
            <p class="text-brand-100 max-w-xl text-lg font-medium">Here's your quick overview of the Growtopia Helper system. Access your favorite calculators and track your daily market moves.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition transform cursor-default">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 text-2xl shadow-inner-light">📈</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Tools</p>
                <p class="text-2xl font-extrabold text-slate-800">1</p>
            </div>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition transform cursor-default">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 text-2xl shadow-inner-light">💎</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Lifetime Value</p>
                <p class="text-2xl font-extrabold text-slate-800">Tracking...</p>
            </div>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition transform cursor-default">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 text-2xl shadow-inner-light">⚡</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">System Status</p>
                <p class="text-2xl font-extrabold text-slate-800">Online</p>
            </div>
        </div>
    </div>

    <!-- Pinned Apps / Tools -->
    <div class="pt-2">
        <h2 class="text-[20px] font-extrabold text-slate-800 mb-5 px-1">Pinned Calculators</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- BBM Card -->
            <a href="{{ route('bbm-provider') }}" class="group bg-white rounded-[32px] p-1 border border-slate-200 shadow-sm hover:shadow-md transition-all block overflow-hidden">
                <div class="bg-slate-50 rounded-[28px] h-36 flex items-center justify-center mb-1 group-hover:bg-[#E2EFFF] transition-colors relative overflow-hidden">
                    <img src="{{ asset('images/bbm/bbm.webp') }}" class="w-16 h-16 object-contain z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-md" alt="BBM">
                    <div class="absolute inset-0 bg-gradient-to-tr from-brand-400/5 to-transparent z-0"></div>
                </div>
                <div class="p-5">
                    <h3 class="font-extrabold text-slate-800 text-lg mb-1 group-hover:text-brand-600 transition-colors">BBM Provider</h3>
                    <p class="text-[13px] text-slate-500 font-medium leading-snug">Track Building Blocks Machine item returns.</p>
                </div>
            </a>
            
            <!-- Surgery Tools Card -->
            <a href="{{ route('surgery-tools') }}" class="group bg-white rounded-[32px] p-1 border border-slate-200 shadow-sm hover:shadow-md transition-all block overflow-hidden">
                <div class="bg-slate-50 rounded-[28px] h-36 flex items-center justify-center mb-1 group-hover:bg-[#E2EFFF] transition-colors relative overflow-hidden">
                    <img src="{{ asset('images/surgery/surg_crate.webp') }}" class="w-16 h-16 object-contain z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-md" alt="Surgery">
                    <div class="absolute inset-0 bg-gradient-to-tr from-brand-400/5 to-transparent z-0"></div>
                </div>
                <div class="p-5">
                    <h3 class="font-extrabold text-slate-800 text-lg mb-1 group-hover:text-brand-600 transition-colors">Surgery Tools</h3>
                    <p class="text-[13px] text-slate-500 font-medium leading-snug">Compute MSurg packs, breakdown costs, and profit margins instantly.</p>
                </div>
            </a>
            
            <!-- Placeholder Card -->
            <div class="bg-white/50 rounded-[32px] p-1 border border-slate-200 border-dashed shadow-sm opacity-60 cursor-not-allowed">
                <div class="bg-slate-50/50 rounded-[28px] h-36 flex items-center justify-center mb-1">
                    <span class="text-3xl text-slate-300">➕</span>
                </div>
                <div class="p-5">
                    <h3 class="font-extrabold text-slate-400 text-lg mb-1">Coming Soon</h3>
                    <p class="text-[13px] text-slate-400 font-medium leading-snug">More market tools are currently in development.</p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

