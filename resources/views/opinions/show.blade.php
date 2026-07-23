@extends('layouts.app')
@section('title', $communityPost->title . " - Community Opinions")

@section('content')
<div class="max-w-3xl mx-auto space-y-8 pb-12">
    <!-- Breadcrumb & Back -->
    <div>
        <a href="{{ route('opinions.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-brand-600 font-bold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Topics
        </a>
    </div>

    <!-- Topic Card -->
    <div class="relative bg-white p-8 md:p-12 rounded-[32px] border border-slate-200/60 shadow-sm overflow-hidden">
        <!-- Glowing Top Accent -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-400 via-brand-500 to-indigo-500"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6 flex-wrap">
                <span class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-600 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider border border-brand-100">
                    <img loading="lazy" src="{{ asset('images/items/megaphone.png') }}" alt="Topic" class="w-4 h-4 object-contain">
                    Discussion Topic
                </span>
                <div class="flex items-center gap-2 text-sm font-bold text-slate-500">
                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 overflow-hidden">
                        <img loading="lazy" src="{{ asset('images/items/guestbook.png') }}" class="w-4 h-4 opacity-70" alt="Admin">
                    </div>
                    <span>Posted by Admin on {{ $communityPost->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight leading-tight">{{ $communityPost->title }}</h1>
            
            <div class="text-slate-700 font-medium leading-relaxed whitespace-pre-wrap text-lg bg-slate-50 p-6 md:p-8 rounded-3xl border border-slate-200">{{ $communityPost->content }}</div>
        </div>
    </div>

    <!-- Submission Form -->
    <div class="bg-slate-50 rounded-[32px] p-8 md:p-10 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center shrink-0">
                <img loading="lazy" src="{{ asset('images/items/pencil.png') }}" class="w-7 h-7 object-contain" alt="Write">
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Share Your Opinion</h3>
                <p class="text-slate-500 font-medium mt-1">Your feedback goes directly to the developer.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 border border-red-200 font-bold p-4 rounded-[20px] mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('opinions.store', $communityPost->id) }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Name <span class="text-slate-400 font-normal">(Optional)</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="What should we call you?" maxlength="255"
                    class="w-full bg-white border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Opinion <span class="text-red-500">*</span></label>
                <textarea name="opinion" required rows="6" placeholder="Tell us what you think..." maxlength="2000"
                    class="w-full bg-white border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">{{ old('opinion') }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-slate-500 font-medium">Please be constructive and respectful.</p>
                    <p class="text-xs text-slate-400 font-medium">Max 2000 characters</p>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto bg-brand-500 hover:bg-brand-600 text-white font-bold py-3.5 px-8 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                Submit Opinion
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
