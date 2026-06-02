@extends('layouts.admin')
@section('title', "What's New Modal Settings")

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="whatsNewSettings({{ json_encode($data) }})">

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Modal Settings</h1>
            <p class="text-slate-500 font-medium mt-1">Configure the "What's New" popup that appears to users.</p>
        </div>
        <button @click="showConfirmModal = true" type="button" class="font-bold rounded-xl transition flex items-center gap-2 text-sm" style="cursor: pointer; background-color: #fef3c7; color: #b45309; padding: 8px 16px;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Force Show to All Users
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold p-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.whats-new.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <input type="hidden" name="version_key" x-model="formData.version_key">

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 space-y-8">
            
            <!-- General Settings -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">General</h3>
                
                <label class="flex items-center gap-3 cursor-pointer group w-max mb-6">
                    <div class="relative" style="width: 48px; height: 28px;">
                        <input type="checkbox" name="is_enabled" class="sr-only" x-model="formData.is_enabled">
                        <!-- Background track -->
                        <div style="display:block; width: 48px; height: 28px; border-radius: 9999px; transition: background-color 0.2s;" :style="{ 'background-color': formData.is_enabled ? '#0e59c3' : '#cbd5e1' }"></div>
                        <!-- Dot -->
                        <div style="position:absolute; left:4px; top:4px; background-color: #fff; width: 20px; height: 20px; border-radius: 9999px; transition: transform 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);" :style="{ 'transform': formData.is_enabled ? 'translateX(20px)' : 'translateX(0)' }"></div>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900 transition-colors select-none">Enable Modal</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Main Title (Normal text)</label>
                        <input type="text" name="title_normal" x-model="formData.title_normal" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium placeholder:text-slate-400" placeholder="e.g. Updates & Community">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Main Title (Highlighted text)</label>
                        <input type="text" name="title_highlight" x-model="formData.title_highlight" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium placeholder:text-slate-400" placeholder="e.g. are now live!">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Main Description</label>
                    <textarea name="description" x-model="formData.description" rows="2" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium placeholder:text-slate-400" placeholder="e.g. Fresh patch notes & community insights waiting for you."></textarea>
                </div>
            </div>

            <!-- Features Array -->
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <h3 class="text-lg font-bold text-slate-800">Features</h3>
                    <button type="button" @click="addFeature()" class="text-sm font-bold flex items-center gap-1 rounded-lg transition" style="cursor: pointer; color: #3b82f6; background-color: #eff6ff; padding: 6px 12px;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Add Feature
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(feature, index) in formData.features" :key="index">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl relative group">
                            <button type="button" @click="removeFeature(index)" class="absolute -top-2 -right-2 text-red-600 hover:text-white p-1.5 rounded-full shadow-sm transition opacity-0 group-hover:opacity-100" style="cursor: pointer; background-color: #fee2e2;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title</label>
                                    <input type="text" :name="`features[${index}][title]`" x-model="feature.title" required class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium" placeholder="e.g. Updates & Patch Notes">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">Badge (Optional)</label>
                                    <input type="text" :name="`features[${index}][badge]`" x-model="feature.badge" class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium" placeholder="e.g. NEW or HOT">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Description</label>
                                <input type="text" :name="`features[${index}][description]`" x-model="feature.description" required class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium" placeholder="e.g. Latest changes to the calculator">
                            </div>
                        </div>
                    </template>
                    <div x-show="formData.features.length === 0" class="text-center p-6 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-medium text-sm">
                        No features added yet.
                    </div>
                </div>
            </div>

            <!-- Buttons Array -->
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <h3 class="text-lg font-bold text-slate-800">Call-to-Action Buttons</h3>
                    <button type="button" @click="addButton()" class="text-sm font-bold flex items-center gap-1 rounded-lg transition" style="cursor: pointer; color: #3b82f6; background-color: #eff6ff; padding: 6px 12px;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Add Button
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(btn, index) in formData.buttons" :key="index">
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl relative group flex gap-4 items-center">
                            <button type="button" @click="removeButton(index)" class="absolute -top-2 -right-2 text-red-600 hover:text-white p-1.5 rounded-full shadow-sm transition opacity-0 group-hover:opacity-100" style="cursor: pointer; background-color: #fee2e2;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-500 mb-1">Button Text</label>
                                <input type="text" :name="`buttons[${index}][text]`" x-model="btn.text" required class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium" placeholder="e.g. View Updates">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-500 mb-1">Target URL</label>
                                <input type="text" :name="`buttons[${index}][url]`" x-model="btn.url" required class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-medium" placeholder="/updates">
                            </div>
                        </div>
                    </template>
                    <div x-show="formData.buttons.length === 0" class="text-center p-6 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-medium text-sm">
                        No buttons added yet.
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3 sticky bottom-4 z-10">
            <button id="save-settings-btn" type="submit" class="text-white font-bold rounded-2xl shadow-lg transition-all active:scale-95 flex items-center gap-2" style="cursor: pointer; background-color: #3b82f6; padding: 14px 32px; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                Save Settings
            </button>
        </div>
    </form>

    <!-- Custom Confirm Modal -->
    <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        
        <!-- Modal Panel -->
        <div x-show="showConfirmModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="showConfirmModal = false"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
             
            <div class="flex sm:items-start gap-4 mb-4 flex-col sm:flex-row items-center text-center sm:text-left">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Force Show Modal?</h3>
                    <p class="text-slate-500 mt-2 text-sm leading-relaxed">This will force the modal to show again for EVERY user by updating the internal version key. Are you sure you want to do this?</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-8">
                <button type="button" @click="showConfirmModal = false" class="px-5 py-2.5 font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition" style="cursor: pointer;">Cancel</button>
                <button type="button" @click="confirmReset()" class="px-5 py-2.5 font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition shadow-lg shadow-amber-500/30" style="cursor: pointer;">Yes, Force Show</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('whatsNewSettings', (initialData) => ({
            showConfirmModal: false,
            formData: {
                is_enabled: initialData.is_enabled ?? true,
                version_key: initialData.version_key || 'v1',
                title_normal: initialData.title_normal || '',
                title_highlight: initialData.title_highlight || '',
                description: initialData.description || '',
                features: initialData.features || [],
                buttons: initialData.buttons || []
            },
            
            addFeature() {
                this.formData.features.push({
                    title: '',
                    description: '',
                    badge: ''
                });
            },
            
            removeFeature(index) {
                this.formData.features.splice(index, 1);
            },
            
            addButton() {
                this.formData.buttons.push({
                    text: '',
                    url: ''
                });
            },
            
            removeButton(index) {
                this.formData.buttons.splice(index, 1);
            },
            
            confirmReset() {
                // Generate a new random version key
                this.formData.version_key = 'v' + Date.now().toString(36);
                this.showConfirmModal = false;
                
                // Automatically submit the form to save the new key instantly!
                setTimeout(() => {
                    let btn = document.getElementById('save-settings-btn');
                    if (btn) btn.click();
                }, 150);
            }
        }));
    });
</script>
@endsection
