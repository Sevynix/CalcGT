@extends('layouts.admin')

@section('title', 'Manage Feedback')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">User Feedback</h1>
        <p class="text-slate-500 font-medium mt-1">Review bug reports, suggestions, and publish your replies.</p>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        @if($feedbacks->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($feedbacks as $feedback)
                <div class="p-6 transition-colors {{ $feedback->is_read ? 'bg-white' : 'bg-slate-50' }}">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        <!-- Left Side: User Report -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                @if($feedback->type === 'bug')
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Bug Report</span>
                                @else
                                    <span class="bg-amber-100 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Suggestion</span>
                                @endif
                                <h3 class="font-bold text-slate-800">{{ $feedback->name }}</h3>
                                <span class="text-sm text-slate-400 font-medium">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <p class="text-slate-600 font-medium whitespace-pre-wrap">{{ $feedback->message }}</p>
                            
                            @if($feedback->image_path)
                                <div class="mt-4">
                                    <a href="{{ asset($feedback->image_path) }}" target="_blank" class="inline-block border border-slate-200 rounded-lg overflow-hidden max-w-[200px] hover:border-brand-500 transition-colors">
                                        <img src="{{ asset($feedback->image_path) }}" alt="Screenshot attached by user" class="w-full h-auto">
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Right Side: Admin Controls -->
                        <div class="md:w-1/3 shrink-0">
                            <form action="{{ route('admin.feedback.update', $feedback->id) }}" method="POST" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Your Reply</label>
                                    <textarea name="admin_reply" rows="3" placeholder="Type your response here..."
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-sm font-medium">{{ old('admin_reply', $feedback->admin_reply) }}</textarea>
                                </div>

                                <div class="flex items-center justify-between">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_published" value="1" {{ $feedback->is_published ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-brand-500 relative"></div>
                                        <span class="ml-2 text-xs font-bold text-slate-600">Publish Publicly</span>
                                    </label>

                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_read" value="1" {{ $feedback->is_read ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                                        <span class="ml-2 text-xs font-bold text-slate-600">Mark Read</span>
                                    </label>
                                </div>
                                
                                <div class="flex gap-2 pt-2 border-t border-slate-200">
                                    <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-700 text-white font-bold py-2 px-3 rounded-lg text-xs transition-colors">
                                        Save Changes
                                    </button>
                                </div>
                            </form>

                            <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" method="POST" class="mt-2 text-right" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">Delete Permanently</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($feedbacks->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Inbox Zero!</h3>
                <p class="text-slate-500 font-medium mt-1">No feedback has been submitted yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
