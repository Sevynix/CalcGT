@extends('layouts.admin')
@section('title', 'Edit Event')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css">
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><img src="{{ asset('images/items/pencil.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="">️ Edit Event</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $tournament->name }}</p>
        </div>
        <a href="{{ route('admin.tournaments.show', $tournament) }}"
           class="px-3 py-2 text-sm font-bold border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-slate-600">
            ← Kembali
        </a>
    </div>

    <div class="max-w-xl" x-data="eventEditForm">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.competitions.update', [$tournament, $competition]) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Nama Event *</label>
                    <input type="text" name="name" value="{{ old('name', $competition->name) }}"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Detail / Deskripsi</label>
                    <textarea name="detail" rows="3"
                              class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 resize-none">{{ old('detail', $competition->detail) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Foto Event (jpg/png, max 2MB)</label>
                    @if($competition->photo_path)
                        <div class="mb-3" x-show="!cropPreview">
                            <img src="{{ asset('storage/' . $competition->photo_path) }}" class="h-28 w-auto rounded-xl object-cover border border-slate-200 shadow-sm" alt="Current Photo">
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input type="checkbox" name="remove_photo" value="1" class="w-4 h-4 text-red-600 rounded border-slate-300 focus:ring-red-500">
                                <span class="text-xs text-red-600 font-bold">Hapus Foto Saat Ini</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="photo" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white" accept="image/*" @change="handleImageUpload" x-ref="photoInput">
                    
                    <template x-if="cropPreview">
                        <div class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 shadow-sm inline-block">
                            <img :src="cropPreview" class="h-32 w-auto object-cover" alt="Cropped preview">
                            <button type="button" @click="clearCrop()" class="absolute top-2 right-2 bg-white/90 text-red-500 hover:bg-red-50 p-1.5 rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                    @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Urutan Reveal *</label>
                    <input type="number" name="reveal_order" value="{{ old('reveal_order', $competition->reveal_order) }}"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                           min="1" required>
                    <p class="text-xs text-slate-400 mt-1">Event akan di-reveal sesuai urutan ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Jumlah Peserta (Slot) *</label>
                    <select name="slot_count" id="slotCountSelect" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300" required onchange="updateBracketOptions()">
                        @foreach([16, 24, 32] as $n)
                            <option value="{{ $n }}" {{ old('slot_count', $competition->slot_count) == $n ? 'selected' : '' }}>
                                {{ $n }} Peserta
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="bracketFormatGroup" style="display: {{ old('has_bracket', $competition->has_bracket) ? 'block' : 'none' }}">
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Format Bracket *</label>
                    <select name="bracket_format" id="bracketFormatSelect"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                            {{ old('has_bracket', $competition->has_bracket) ? 'required' : '' }}>
                        <!-- Populated by JS -->
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="has_bracket" value="0">
                        <input type="checkbox" name="has_bracket" id="hasBracketCheck" value="1"
                               onchange="toggleBracketFormat()"
                               {{ old('has_bracket', $competition->has_bracket) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                        <span class="text-sm text-slate-700 font-medium">Gunakan Bracket Pertandingan</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="has_registration" value="0">
                        <input type="checkbox" name="has_registration" value="1"
                               {{ old('has_registration', $competition->has_registration) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                        <span class="text-sm text-slate-700 font-medium">Aktifkan Pendaftaran Publik</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-bold rounded-xl transition-colors">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.tournaments.show', $tournament) }}"
                       class="px-5 py-2 text-sm font-bold border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-slate-600">
                        Batal
                    </a>
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
                        <h3 class="text-lg font-bold text-slate-900">Crop Foto Event</h3>
                        <p class="text-sm text-slate-500 font-medium">Aspect Ratio: 16:9</p>
                    </div>
                    <button type="button" @click="cancelCrop()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Cropper Canvas Area -->
                <div class="bg-slate-950 flex-1 overflow-hidden flex items-center justify-center" style="min-height: 280px; max-height: 440px;">
                    <img id="cropperImageEdit" src="" alt="Image to crop" style="display: block; max-width: 100%;">
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
</div>

@php
    $bracketOptionsMap = [];
    foreach([16, 24, 32] as $sc) {
        $bracketOptionsMap[$sc] = \App\Services\Bracket\FixedBracketOptionsResolver::getOptions($sc);
    }
    $currentFormat = old('bracket_format', $competition->bracket_format);
@endphp

<script>
const bracketOptionsMap = @json($bracketOptionsMap);
const currentFormat = @json($currentFormat);

function toggleBracketFormat() {
    const hasBracket = document.getElementById('hasBracketCheck').checked;
    const group = document.getElementById('bracketFormatGroup');
    const select = document.getElementById('bracketFormatSelect');

    group.style.display = hasBracket ? 'block' : 'none';
    if (hasBracket) {
        select.setAttribute('required', 'required');
        updateBracketOptions();
    } else {
        select.removeAttribute('required');
    }
}

function updateBracketOptions() {
    const sc = document.getElementById('slotCountSelect').value;
    const select = document.getElementById('bracketFormatSelect');
    const options = bracketOptionsMap[sc] || [];

    select.innerHTML = '';
    options.forEach(opt => {
        const el = document.createElement('option');
        el.value = opt.code;
        el.textContent = opt.label;
        if (opt.code === currentFormat) el.selected = true;
        select.appendChild(el);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    toggleBracketFormat();
});
</script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventEditForm', () => ({
        isCropModalOpen: false,
        cropperInstance: null,
        cropPreview: '',

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.isCropModalOpen = true;
                this.$nextTick(() => {
                    const img = document.getElementById('cropperImageEdit');
                    img.src = e.target.result;
                    
                    if (this.cropperInstance) {
                        this.cropperInstance.destroy();
                        this.cropperInstance = null;
                    }
                    img.onload = () => {
                        this.cropperInstance = new Cropper(img, {
                            aspectRatio: 16/9,
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
                const originalName = this.$refs.photoInput.files[0]?.name || 'event_photo.jpg';
                const croppedFile = new File([blob], originalName, { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                this.$refs.photoInput.files = dt.files;
                
                this.cropPreview = canvas.toDataURL('image/jpeg');
                this.closeCropModal();
            }, 'image/jpeg', 0.92);
        },

        cancelCrop() {
            this.$refs.photoInput.value = '';
            this.cropPreview = '';
            this.closeCropModal();
        },
        
        clearCrop() {
            this.$refs.photoInput.value = '';
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
