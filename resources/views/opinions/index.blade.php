@extends('layouts.app')
@section('title', "Community Opinions")
@section('meta_description', "Have your say! Share your opinions on new features, calculators, and tools being added to GrowtopiaHelp.")

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 flex items-center justify-center gap-3">
            <img loading="lazy" src="{{ asset('images/items/megaphone.png') }}" alt="Community"
                class="w-12 h-12 object-contain drop-shadow-sm">
            Community Opinions
        </h1>
        <p class="text-slate-500 mt-4 text-lg">Have your say! Help shape the future of Growtopia Calculator.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold p-4 rounded-[20px] flex items-center gap-3 max-w-3xl mx-auto">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($posts->count() > 0)
        <div class="space-y-6 max-w-3xl mx-auto">
            @foreach($posts as $index => $post)
                <a href="{{ route('opinions.show', $post->id) }}" class="block bg-white p-6 md:p-8 rounded-[32px] border border-slate-200/60 shadow-sm transition-all hover:shadow-md hover:border-brand-300 hover:-translate-y-1 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-brand-500 transition-all group-hover:w-2"></div>
                    
                    <div class="flex items-center justify-between gap-4 flex-wrap mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($index === 0)
                                <span class="bg-brand-100 text-brand-600 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Newest Topic</span>
                            @endif
                            <span class="text-sm font-bold text-slate-400">{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <span class="text-brand-500 text-sm font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            Share Opinion <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                    
                    <h3 class="text-2xl font-extrabold text-slate-800 mb-3 group-hover:text-brand-600 transition-colors">{{ $post->title }}</h3>
                    
                    <p class="text-slate-600 font-medium leading-relaxed line-clamp-2">{{ $post->content }}</p>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-[32px] p-12 shadow-sm border border-slate-100 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-200">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">No Active Topics</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto">There are currently no active discussion topics. Check back later!</p>
        </div>
    @endif
</div>
@endsection
