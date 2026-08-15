@extends('layouts.admin')

@section('title', 'Manage Patch Notes')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Patch Notes & Updates</h1>
            <p class="text-slate-500 font-medium mt-1">Write and publish updates to let users know what's new.</p>
        </div>
        <a href="{{ route('admin.patch-notes.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Create New Update
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        @if($patchNotes->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($patchNotes as $note)
                <div class="p-6 transition-colors hover:bg-slate-50 flex flex-col md:flex-row gap-6 justify-between items-start">
                    
                    <!-- Left Side: Note Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            @if($note->is_published)
                                <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Published</span>
                            @else
                                <span class="bg-amber-100 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Draft</span>
                            @endif
                            @if($note->version)
                                <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-md">{{ $note->version }}</span>
                            @endif
                            <h3 class="font-bold text-slate-800 text-lg">{{ $note->title }}</h3>
                        </div>
                        
                        <div class="text-slate-500 text-sm font-medium mb-3">Created {{ $note->created_at->format('M d, Y g:i A') }}</div>
                        
                        <p class="text-slate-600 text-sm line-clamp-3 whitespace-pre-wrap">{{ $note->content }}</p>
                    </div>

                    <!-- Right Side: Admin Controls -->
                    <div class="shrink-0 flex gap-2">
                        <a href="{{ route('admin.patch-notes.edit', $note->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-4 rounded-xl transition-colors text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.patch-notes.destroy', $note->id) }}" method="POST" @submit.prevent="$store.modal.show('Are you sure you want to delete this patch note?', () => $el.submit(), 'Confirm Delete', 'Yes, Delete', 'danger')">
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
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Patch Notes Yet</h3>
                <p class="text-slate-500 font-medium mt-1 mb-6">Create your first update to let your users know what's new.</p>
                <a href="{{ route('admin.patch-notes.create') }}" class="inline-flex bg-brand-500 hover:bg-brand-600 text-white font-bold py-2 px-6 rounded-xl transition-colors">
                    Create Update
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
