@extends('layouts.app')
@section('title', 'Community Feedback Board')
@section('meta_description', 'View community suggestions, bug reports, and admin responses for Growtopia Calculator.')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                <svg class="w-10 h-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                Feedback Board
            </h1>
            <p class="text-slate-500 font-medium mt-2 text-lg">See what the community is suggesting and reporting.</p>
        </div>
        <a href="{{ route('feedback.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-md hover:shadow-lg active:scale-95">
            Submit Feedback
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </a>
    </div>

    <!-- Feed -->
    <div class="space-y-6">
        @forelse($publishedFeedbacks as $feedback)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <!-- User Report -->
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-3">
                        @if($feedback->type === 'bug')
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Bug Report</span>
                        @else
                            <span class="bg-amber-100 text-amber-600 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Suggestion</span>
                        @endif
                        <h3 class="font-bold text-slate-800 text-lg">{{ $feedback->name }}</h3>
                        <span class="text-sm text-slate-400 font-medium ml-auto">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 text-[15px] font-medium whitespace-pre-wrap leading-relaxed">{{ $feedback->message }}</p>
                    
                    @if($feedback->image_path)
                        <div class="mt-4">
                            <a href="{{ asset($feedback->image_path) }}" target="_blank" class="inline-block border border-slate-200 rounded-xl overflow-hidden max-w-sm hover:border-brand-500 transition-colors shadow-sm">
                                <img src="{{ asset($feedback->image_path) }}" alt="Screenshot attached by user" class="w-full h-auto">
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Admin Reply -->
                @if($feedback->admin_reply)
                    <div class="bg-slate-50 p-6 md:px-8 border-t border-slate-100">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center shrink-0 border border-brand-200">
                                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 flex items-center gap-2">Admin Response <span class="bg-brand-500 text-white text-[10px] uppercase px-2 py-0.5 rounded-full tracking-widest">Official</span></h4>
                                <p class="text-slate-600 font-medium text-sm mt-1 whitespace-pre-wrap leading-relaxed">{{ $feedback->admin_reply }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200 shadow-inner">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">No Feedback Yet</h3>
                <p class="text-slate-500 font-medium mt-1 mb-6">Be the first to submit a suggestion or report a bug!</p>
                <a href="{{ route('feedback.create') }}" class="text-brand-600 font-bold hover:text-brand-700 transition-colors">Submit Feedback →</a>
            </div>
        @endforelse
    </div>
    
    @if($publishedFeedbacks->hasPages())
        <div class="mt-8">
            {{ $publishedFeedbacks->links() }}
        </div>
    @endif
</div>
@endsection
