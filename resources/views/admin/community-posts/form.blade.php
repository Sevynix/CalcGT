@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Community Topic' : 'Create Community Topic')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.community-posts.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ isset($post) ? 'Edit Topic' : 'New Topic' }}</h1>
            <p class="text-slate-500 font-medium mt-1">{{ isset($post) ? 'Make changes to an existing discussion topic.' : 'Create a new topic to gather opinions from the community.' }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 border border-red-200 font-bold p-4 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden p-6 md:p-8">
        <form action="{{ isset($post) ? route('admin.community-posts.update', $post->id) : route('admin.community-posts.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($post))
                @method('PUT')
            @endif

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Topic Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required placeholder="e.g. What new calculator should we add next?"
                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
            </div>

            <!-- Content Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Description / Details <span class="text-red-500">*</span></label>
                <textarea name="content" required rows="6" placeholder="Provide some context for the community..."
                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">{{ old('content', $post->content ?? '') }}</textarea>
                <p class="text-xs text-slate-500 font-medium">Explain what you want the users to share their opinions on.</p>
            </div>

            <!-- Publish Toggle -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800">Publish Immediately?</h4>
                    <p class="text-sm text-slate-500 font-medium">If turned off, this topic will be completely hidden from the public.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $post->is_published ?? true) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                </label>
            </div>

            <div class="pt-6 flex flex-wrap sm:flex-nowrap justify-end gap-3">
                <a href="{{ route('admin.community-posts.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl transition-colors whitespace-nowrap">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    {{ isset($post) ? 'Save Changes' : 'Create Topic' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
