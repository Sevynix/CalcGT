@extends('layouts.admin')

@section('title', 'Manage Community Posts')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Community Opinion Posts</h1>
            <p class="text-slate-500 font-medium mt-1">Create topics for your community to discuss and share opinions.</p>
        </div>
        <a href="{{ route('admin.community-posts.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Create New Topic
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        @if($posts->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($posts as $post)
                <div class="p-6 transition-colors hover:bg-slate-50 flex flex-col md:flex-row gap-6 justify-between items-start">
                    
                    <!-- Left Side: Post Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            @if($post->is_published)
                                <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Active</span>
                            @else
                                <span class="bg-amber-100 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Disabled</span>
                            @endif
                            <h3 class="font-bold text-slate-800 text-lg">{{ $post->title }}</h3>
                        </div>
                        
                        <div class="text-slate-500 text-sm font-medium mb-3">Created {{ $post->created_at->format('M d, Y g:i A') }} &bull; <a href="{{ route('admin.community-opinions.index', ['post_id' => $post->id]) }}" class="text-brand-500 hover:text-brand-600 underline">{{ $post->opinions_count }} Responses</a></div>
                        
                        <p class="text-slate-600 text-sm line-clamp-3 whitespace-pre-wrap">{{ $post->content }}</p>
                    </div>

                    <!-- Right Side: Admin Controls -->
                    <div class="shrink-0 flex gap-2">
                        <a href="{{ route('admin.community-posts.edit', $post->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.community-posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this topic? This will also delete all user opinions associated with it.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Community Topics Yet</h3>
                <p class="text-slate-500 font-medium mt-1 mb-6">Create your first discussion topic to gather community opinions.</p>
                <a href="{{ route('admin.community-posts.create') }}" class="inline-flex bg-brand-500 hover:bg-brand-600 text-white font-bold py-2 px-6 rounded-xl transition-colors">
                    Create Topic
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
