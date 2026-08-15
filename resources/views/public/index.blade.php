@extends('layouts.app')
@section('title', 'Tournaments')
@section('meta_description', 'Daftar tournament aktif di growtopiacalculator.com')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    {{-- Hero Section --}}
    <div class="relative py-12 px-6 lg:px-10 mb-12 overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 border border-slate-200/80 dark:border-slate-700/50 shadow-xl dark:shadow-2xl">
        <!-- Abstract Background Elements -->
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-brand-500/10 dark:bg-brand-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-72 h-72 bg-sky-500/10 dark:bg-sky-500/20 rounded-full blur-[80px] pointer-events-none"></div>
        <img src="{{ asset('images/items/trophy.png') }}" class="absolute -right-4 top-4 w-72 h-72 opacity-[0.06] dark:opacity-[0.04] pointer-events-none select-none object-contain drop-shadow-2xl grayscale" alt="Trophy">

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 mb-6 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 dark:bg-amber-400 animate-pulse"></span>
                <span class="text-[11px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-[0.2em]">Growtopia Tournament</span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-sky-500 dark:from-brand-400 dark:to-sky-400 tracking-tight mb-4 leading-tight">
                Event & Kompetisi
            </h1>
            
            <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl max-w-2xl font-medium mb-8">
                Daftar tournament yang sedang aktif maupun yang akan datang
            </p>

            @if(!$tournaments->isEmpty())
            <div class="flex flex-wrap gap-10">
                <div class="flex flex-col">
                    <span class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $tournaments->count() }}</span>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest mt-2">Tournament</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $tournaments->sum('competitions_count') }}</span>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest mt-2">Lomba Total</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Tournaments List --}}
    <div>
        @if($tournaments->isEmpty())
            <div class="text-center py-20 px-6 bg-white dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700/80 rounded-[2.5rem] max-w-2xl mx-auto shadow-sm">
                <div class="text-6xl mb-6 opacity-80">🏟️</div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-3 tracking-tight">Belum Ada Tournament</h3>
                <p class="text-slate-500 dark:text-slate-400 text-base font-medium">Ikuti update kami untuk informasi tournament berikutnya yang akan datang.</p>
            </div>
        @else
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest whitespace-nowrap">Semua Tournament</h2>
                <div class="h-px w-full bg-slate-200 dark:bg-slate-700/60"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tournaments as $t)
                <a href="{{ route('public.tournament.show', $t) }}" class="group block relative p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col h-full min-h-[220px]">
                    
                    <!-- Hover gradient effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-5">
                            @php
                                $statusColors = match($t->status) {
                                    'draft' => 'bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-600',
                                    'active' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                    'hidden' => 'bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-600',
                                    default => 'bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 border-brand-200 dark:border-brand-800/50',
                                };
                            @endphp
                            <span class="px-2.5 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusColors }} shadow-sm">
                                {{ $t->status === 'test_drive' ? 'demo' : str_replace('_', ' ', $t->status) }}
                            </span>
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-2.5 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm tracking-wide">
                                {{ $t->competitions_count }} Lomba
                            </span>
                        </div>

                        <h3 class="text-xl font-black text-slate-800 dark:text-slate-100 mb-2.5 leading-tight tracking-tight group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                            {{ $t->name }}
                        </h3>
                        
                        @if($t->description)
                            <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-6 line-clamp-2">
                                {{ $t->description }}
                            </p>
                        @endif

                        <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-600 dark:text-brand-400 flex items-center gap-1.5 transition-all">
                                Lihat Detail
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
