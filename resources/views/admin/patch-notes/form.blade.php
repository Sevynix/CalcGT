@extends('layouts.admin')

@section('title', isset($patchNote) ? 'Edit Patch Note' : 'Create Patch Note')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.patch-notes.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ isset($patchNote) ? 'Edit Update' : 'New Update' }}</h1>
            <p class="text-slate-500 font-medium mt-1">{{ isset($patchNote) ? 'Make changes to an existing patch note.' : 'Write a new patch note to let your users know what changed.' }}</p>
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
        <form action="{{ isset($patchNote) ? route('admin.patch-notes.update', $patchNote->id) : route('admin.patch-notes.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($patchNote))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Title Field -->
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $patchNote->title ?? '') }}" required placeholder="e.g. Added ATM Calculator & Bug Fixes"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                </div>

                <!-- Version Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Version Label <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="version" value="{{ old('version', $patchNote->version ?? '') }}" placeholder="e.g. v1.2.0"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                </div>
            </div>

            <!-- Content Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Content <span class="text-red-500">*</span></label>
                <textarea name="content" required rows="10" placeholder="Write what's new here..."
                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">{{ old('content', $patchNote->content ?? '') }}</textarea>
                <p class="text-xs text-slate-500 font-medium">Formatting is simple text right now as requested. Line breaks will be preserved.</p>
            </div>

            <!-- Publish Toggle -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800">Publish Immediately?</h4>
                    <p class="text-sm text-slate-500 font-medium">If turned off, this will be saved as a draft and hidden from users.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $patchNote->is_published ?? true) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                </label>
            </div>

            <div class="pt-6 flex flex-wrap sm:flex-nowrap justify-end gap-3">
                <a href="{{ route('admin.patch-notes.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl transition-colors whitespace-nowrap">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    {{ isset($patchNote) ? 'Save Changes' : 'Create Update' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
