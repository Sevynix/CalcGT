@extends('layouts.admin')

@section('title', 'View Community Opinions')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Community Opinions</h1>
            <p class="text-slate-500 font-medium mt-1">Read what the community is saying about your topics.</p>
        </div>
        <a href="{{ route('admin.community-posts.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-colors">
            Manage Topics
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        @if($opinions->count() > 0)
            <div class="space-y-8 p-6">
                @foreach($opinions->groupBy('community_post_id') as $postId => $postOpinions)
                    @php $post = $postOpinions->first()->post; @endphp
                    
                    <div class="bg-slate-50 rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                        <!-- Post Header -->
                        <div class="bg-white border-b border-slate-200 p-5 flex items-center justify-between">
                            <div>
                                <span class="bg-brand-100 text-brand-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">Discussion Topic</span>
                                <h2 class="text-xl font-extrabold text-slate-800">{{ $post->title }}</h2>
                            </div>
                            <div class="text-sm font-bold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg">
                                {{ $postOpinions->count() }} {{ Str::plural('Opinion', $postOpinions->count()) }}
                            </div>
                        </div>

                        <!-- Opinions List -->
                        <div class="divide-y divide-slate-200">
                            @foreach($postOpinions as $opinion)
                            <div class="p-5 transition-colors {{ $opinion->is_read ? '' : 'bg-brand-50/30' }}">
                                <div class="flex flex-col md:flex-row gap-5">
                                    
                                    <!-- Left Side: User Opinion -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center border border-slate-300">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-800 text-sm leading-none">{{ $opinion->name ?: 'Anonymous' }}</h3>
                                                <span class="text-xs text-slate-400 font-medium">{{ $opinion->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if(!$opinion->is_read)
                                                <span class="w-2 h-2 rounded-full bg-brand-500 ml-auto md:ml-2"></span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-slate-700 font-medium whitespace-pre-wrap text-sm bg-white p-4 rounded-2xl border border-slate-200/60 shadow-sm">{{ $opinion->opinion }}</p>
                                    </div>

                                    <!-- Right Side: Admin Controls -->
                                    <div class="shrink-0 flex md:flex-col gap-2 pt-2 md:pt-10">
                                        @if(!$opinion->is_read)
                                            <form action="{{ route('admin.community-opinions.update', $opinion->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-2 px-4 rounded-xl transition-colors text-xs flex items-center justify-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Mark Read
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.community-opinions.destroy', $opinion->id) }}" method="POST" class="w-full" @submit.prevent="$store.modal.show('Are you sure you want to delete this opinion?', () => $el.submit(), 'Confirm Delete', 'Yes, Delete', 'danger')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-xl transition-colors text-xs flex items-center justify-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Opinions Yet</h3>
                <p class="text-slate-500 font-medium mt-1 mb-6">When users submit their opinions on your topics, they will appear here.</p>
            </div>
        @endif
    </div>
</div>
@endsection
