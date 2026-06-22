@extends('layouts.app')
@section('title', 'Submit Feedback')
@section('meta_description', 'Submit bugs or suggestions for Growtopia Calculator.')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    
    <div class="text-center space-y-4">
        <div class="w-16 h-16 bg-brand-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto border border-brand-100 dark:border-slate-700 shadow-inner">
            <svg class="w-8 h-8 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Send Feedback</h1>
            <p class="text-slate-500 font-medium mt-2">Found a bug or have a suggestion? Let us know!</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex flex-col items-center gap-2 text-center shadow-sm">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold text-lg">{{ session('success') }}</span>
            <a href="{{ route('feedback.index') }}" class="text-emerald-700 font-bold hover:underline mt-2 text-sm">View Feedback Board →</a>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 md:p-8 relative overflow-hidden">
            <!-- Decorative blobs -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-brand-50 dark:bg-brand-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>

            <form action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-medium placeholder:font-normal placeholder:text-slate-400" placeholder="e.g. User">
                    @error('name')<p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Feedback Type</label>
                    <div class="grid grid-cols-2 gap-4" x-data="{ selectedType: '{{ old('type', '') }}' }">
                        <label class="relative outline-none group cursor-pointer" @click="selectedType = 'bug'">
                            <input type="radio" name="type" value="bug" class="sr-only" required x-model="selectedType">
                            <div class="w-full rounded-[20px] border-2 transition-all flex flex-col items-center justify-center p-4 relative"
                                 :class="selectedType === 'bug' 
                                    ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm' 
                                    : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#151b2b] hover:border-slate-200 dark:hover:border-[#2a3449] grayscale-[0.8] opacity-70 hover:opacity-100 hover:grayscale-[0.4]'">
                                
                                <div class="h-16 w-full flex items-center justify-center mb-2">
                                    <img src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation Mark" 
                                         class="max-h-full max-w-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] transition-all duration-300"
                                         :class="selectedType === 'bug' ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                                </div>
                                
                                <span class="font-bold leading-tight text-center w-full transition-colors duration-300"
                                      :class="selectedType === 'bug' ? 'text-brand-700 dark:text-brand-300' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200'">
                                    Bug Report
                                </span>
                            </div>
                        </label>
                        <label class="relative outline-none group cursor-pointer" @click="selectedType = 'suggestion'">
                            <input type="radio" name="type" value="suggestion" class="sr-only" required x-model="selectedType">
                            <div class="w-full rounded-[20px] border-2 transition-all flex flex-col items-center justify-center p-4 relative"
                                 :class="selectedType === 'suggestion' 
                                    ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm' 
                                    : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#151b2b] hover:border-slate-200 dark:hover:border-[#2a3449] grayscale-[0.8] opacity-70 hover:opacity-100 hover:grayscale-[0.4]'">
                                
                                <div class="h-16 w-full flex items-center justify-center mb-2">
                                    <img src="{{ asset('images/items/letter.png') }}" alt="Suggestion Letter" 
                                         class="max-h-full max-w-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] transition-all duration-300"
                                         :class="selectedType === 'suggestion' ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                                </div>
                                
                                <span class="font-bold leading-tight text-center w-full transition-colors duration-300"
                                      :class="selectedType === 'suggestion' ? 'text-brand-700 dark:text-brand-300' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200'">
                                    Suggestion
                                </span>
                            </div>
                        </label>
                    </div>
                    @error('type')<p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Message</label>
                    <textarea name="message" rows="4" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-medium placeholder:font-normal placeholder:text-slate-400" placeholder="Please describe the bug or your idea in detail..."></textarea>
                    @error('message')<p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Attach Screenshot (Optional)</label>
                    <div x-data="{ fileName: '', previewUrl: '' }" class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4 bg-slate-50 dark:bg-[#151b2b] text-center hover:bg-slate-100 dark:hover:bg-[#1c2438] transition-colors cursor-pointer relative group overflow-hidden">
                        <input type="file" name="image" accept="image/*" 
                            @change="
                                if ($event.target.files.length > 0) {
                                    fileName = $event.target.files[0].name;
                                    previewUrl = URL.createObjectURL($event.target.files[0]);
                                } else {
                                    fileName = '';
                                    previewUrl = '';
                                }
                            "
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <!-- State 1: No File -->
                        <div x-show="!previewUrl" class="py-2">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-400">Click to upload or drag and drop</span>
                            <p class="text-xs text-slate-400 mt-1 font-medium">PNG, JPG, up to 5MB</p>
                        </div>

                        <!-- State 2: File Selected -->
                        <div x-show="previewUrl" style="display: none;" class="flex items-center gap-4 text-left relative z-0 bg-white dark:bg-[#111622] rounded-lg p-2 border border-slate-100 dark:border-[#2a3449]">
                            <div class="w-14 h-14 rounded-md overflow-hidden shrink-0 border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-[#151b2b]">
                                <img :src="previewUrl" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow min-w-0">
                                <span x-text="fileName" class="text-sm font-bold text-brand-600 dark:text-brand-400 block truncate"></span>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Click to change image</p>
                            </div>
                            <div class="shrink-0 text-slate-400 group-hover:text-brand-500 transition-colors pr-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                        </div>
                    </div>
                    @error('image')<p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-md active:scale-[0.98] flex items-center justify-center gap-2">
                        Submit Feedback
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('feedback.index') }}" class="text-slate-500 font-bold hover:text-brand-600 transition-colors">View Community Feedback Board →</a>
        </div>
    @endif
</div>
@endsection
