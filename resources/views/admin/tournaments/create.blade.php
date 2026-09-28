@extends('layouts.admin')
@section('title', 'Buat Tournament Baru')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css">
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="tournamentForm">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tournaments.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tournament Baru</h1>
            <p class="text-slate-500 font-medium mt-1">Buat tournament baru untuk komunitas.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden p-6 md:p-8">
        <form method="POST" action="{{ route('admin.tournaments.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Name Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Nama Tournament <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: GT Special Day #1"
                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                @error('name')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat tournament..."
                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium resize-none">{{ old('description') }}</textarea>
            </div>

            <!-- Discord URL Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Discord URL <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:border-brand-500 transition-colors">
                    <div class="pl-4 pr-2 flex items-center shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 16 16" fill="#5865F2" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.545 2.907a13.227 13.227 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.19 12.19 0 0 0-3.658 0 8.258 8.258 0 0 0-.412-.833.051.051 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.041.041 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032c.001.014.01.028.021.037a13.276 13.276 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019c.308-.42.582-.863.818-1.329a.05.05 0 0 0-.01-.059.051.051 0 0 0-.018-.011 8.875 8.875 0 0 1-1.248-.595.05.05 0 0 1-.02-.066.051.051 0 0 1 .015-.019c.084-.063.168-.129.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.052.052 0 0 1 .053.007c.08.066.164.132.248.195a.051.051 0 0 1-.004.085 8.254 8.254 0 0 1-1.249.594.05.05 0 0 0-.03.03.052.052 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.235 13.235 0 0 0 4.001-2.02.049.049 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.034.034 0 0 0-.02-.019Zm-8.198 7.307c-.789 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612Zm5.316 0c-.788 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612Z"/>
                        </svg>
                    </div>
                    <input type="text" name="discord_url" value="{{ old('discord_url') }}"
                        placeholder="https://discord.gg/..."
                        class="flex-1 bg-transparent text-slate-900 py-3 pr-4 outline-none font-medium">
                </div>
                <p class="text-xs text-slate-400">Jika diisi, tombol Discord akan muncul di halaman timeline event. Kosongkan untuk menyembunyikan.</p>
                @error('discord_url')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Status Awal <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                        <option value="draft" {{ old('status','draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="test_drive" {{ old('status') == 'test_drive' ? 'selected' : '' }}>Demo</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>

                <!-- Aspect Ratio Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Aspect Ratio Banner <span class="text-red-500">*</span></label>
                    <select name="banner_aspect_ratio" x-model="aspectRatio" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-3 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                        <option value="3:2">3:2 (Lebih Kotak)</option>
                        <option value="16:9">16:9 (Lebih Lebar)</option>
                    </select>
                </div>
            </div>

            <!-- Banner Image Field -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Banner Image <span class="text-slate-400 font-normal">(Optional)</span></label>
                <div class="flex items-center gap-4">
                    <input type="file" name="banner_image" accept="image/*" @change="handleImageUpload" x-ref="bannerInput"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-colors font-medium">
                </div>
                <!-- Cropped Preview -->
                <template x-if="cropPreview">
                    <div class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 shadow-sm inline-block">
                        <img :src="cropPreview" class="h-32 w-auto object-cover" alt="Cropped preview">
                        <button type="button" @click="clearCrop()" class="absolute top-2 right-2 bg-white/90 text-red-500 hover:bg-red-50 p-1.5 rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                @error('banner_image')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="pt-6 flex flex-wrap sm:flex-nowrap justify-end gap-3">
                <a href="{{ route('admin.tournaments.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl transition-colors whitespace-nowrap">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    Buat Tournament
                </button>
            </div>
        </form>
    </div>

    <!-- Crop Modal -->
    <div x-show="isCropModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="cancelCrop()"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col" style="max-height: 90vh;">
            <!-- Header -->
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Crop Banner Image</h3>
                    <p class="text-sm text-slate-500 font-medium" x-text="'Aspect Ratio: ' + aspectRatio"></p>
                </div>
                <button type="button" @click="cancelCrop()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Cropper Canvas Area -->
            <div class="bg-slate-950 flex-1 overflow-hidden flex items-center justify-center" style="min-height: 280px; max-height: 440px;">
                <img id="cropperImageCreate" src="" alt="Image to crop" style="display: block; max-width: 100%;">
            </div>

            <!-- Footer -->
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3 shrink-0 rounded-b-3xl">
                <button type="button" @click="cancelCrop()" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" @click="confirmCrop()" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-sm transition-colors">
                    Terapkan Crop
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tournamentForm', () => ({
        aspectRatio: '3:2',
        isCropModalOpen: false,
        cropperInstance: null,
        cropPreview: '',

        init() {
            this.$watch('aspectRatio', value => {
                if (this.cropperInstance) {
                    this.cropperInstance.setAspectRatio(value === '3:2' ? 3/2 : 16/9);
                }
            });
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.isCropModalOpen = true;
                this.$nextTick(() => {
                    const img = document.getElementById('cropperImageCreate');
                    img.src = e.target.result;
                    
                    if (this.cropperInstance) {
                        this.cropperInstance.destroy();
                        this.cropperInstance = null;
                    }
                    img.onload = () => {
                        this.cropperInstance = new Cropper(img, {
                            aspectRatio: this.aspectRatio === '3:2' ? 3/2 : 16/9,
                            viewMode: 1,
                            autoCropArea: 1,
                            responsive: true,
                            background: false,
                        });
                    };
                });
            };
            reader.readAsDataURL(file);
        },

        confirmCrop() {
            if (!this.cropperInstance) return;
            const canvas = this.cropperInstance.getCroppedCanvas();
            canvas.toBlob((blob) => {
                const originalName = this.$refs.bannerInput.files[0]?.name || 'banner.jpg';
                const croppedFile = new File([blob], originalName, { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                this.$refs.bannerInput.files = dt.files;
                
                this.cropPreview = canvas.toDataURL('image/jpeg');
                this.closeCropModal();
            }, 'image/jpeg', 0.92);
        },

        cancelCrop() {
            this.$refs.bannerInput.value = '';
            this.cropPreview = '';
            this.closeCropModal();
        },
        
        clearCrop() {
            this.$refs.bannerInput.value = '';
            this.cropPreview = '';
        },

        closeCropModal() {
            if (this.cropperInstance) {
                this.cropperInstance.destroy();
                this.cropperInstance = null;
            }
            this.isCropModalOpen = false;
        }
    }));
});
</script>
@endsection
