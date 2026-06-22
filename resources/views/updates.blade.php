@extends('layouts.app')
@section('title', "Updates & Patch Notes")
@section('meta_description', "Check out the latest updates, bug fixes, and new calculators added to GrowtopiaHelp.")

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 flex items-center justify-center gap-3">
            <img src="{{ asset('images/items/guestbook.png') }}" alt="Updates"
                class="w-12 h-12 object-contain drop-shadow-sm">
            Updates & Patch Notes
        </h1>
        <p class="text-slate-500 mt-4 text-lg">Stay in the loop with what's new and what's been fixed.</p>
    </div>

    @if($patchNotes->count() > 0)
        <div class="space-y-6 max-w-3xl mx-auto relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px before:h-full before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
            @foreach($patchNotes as $index => $note)
                <div class="relative flex items-start gap-6 group">
                    
                    <!-- Timeline Dot -->
                    <div class="relative z-10 flex items-center justify-center w-12 h-12 rounded-full border-4 border-white dark:border-[#0b0f19] bg-brand-500 text-white shrink-0 shadow-sm mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="flex-1 bg-white dark:bg-[#151b2b] p-6 md:p-8 rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm transition-all hover:shadow-md hover:border-brand-200 dark:hover:border-brand-700">
                        <div class="flex items-center gap-3 mb-3 flex-wrap">
                            @if($index === 0)
                                <span class="bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Latest</span>
                            @endif
                            @if($note->version)
                                <span class="bg-slate-100 dark:bg-[#1c2438] text-slate-600 dark:text-slate-400 text-xs font-bold px-3 py-1 rounded-md">{{ $note->version }}</span>
                            @endif
                            <span class="text-sm font-bold text-slate-400">{{ $note->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mb-4">{{ $note->title }}</h3>
                        
                        <p class="text-slate-600 dark:text-slate-300 font-medium leading-relaxed whitespace-pre-wrap">{{ $note->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-[32px] p-12 shadow-sm border border-slate-100 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-200">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">No Updates Yet</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto">We're just getting started! Check back here soon for news about new calculators, tools, and bug fixes.</p>
        </div>
    @endif
</div>
@endsection
