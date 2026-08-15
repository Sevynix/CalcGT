@extends('layouts.admin')
@section('title', $tournament->name)

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css">
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.tournaments.index') }}" class="text-slate-400 hover:text-slate-600 text-sm transition-colors">← Tournaments</a>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $tournament->name }}</h1>
            <div class="flex gap-2 mt-2">
                @php
                    $sc = ['active'=>'bg-emerald-100 text-emerald-700','test_drive'=>'bg-indigo-100 text-indigo-700','finished'=>'bg-slate-100 text-slate-500','draft'=>'bg-amber-100 text-amber-700'];
                    $dots = ['active'=>'bg-emerald-500','test_drive'=>'bg-indigo-500','finished'=>'bg-slate-400','draft'=>'bg-amber-400'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $sc[$tournament->status] ?? 'bg-slate-100 text-slate-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dots[$tournament->status] ?? 'bg-slate-400' }}"></span>
                    {{ $tournament->status === 'test_drive' ? 'Demo' : ucfirst($tournament->status) }}
                </span>
                @if($tournament->is_visible)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Tampil
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Hidden
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.tournaments.toggle-visibility', $tournament) }}">
                @csrf
                <button class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold rounded-xl transition-colors {{ $tournament->is_visible ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                    @if($tournament->is_visible)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Sembunyikan
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Tampilkan
                    @endif
                </button>
            </form>
            <a href="{{ route('admin.tournaments.edit', $tournament) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors text-slate-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('admin.tournaments.destroy', $tournament) }}" @submit.prevent="$store.modal.show('Yakin ingin menghapus tournament ini beserta semua eventnya secara permanen? Tindakan ini tidak bisa dibatalkan.', () => $el.submit(), 'Hapus Tournament?', 'Ya, Hapus', 'danger')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold border border-red-200 rounded-xl bg-red-50 hover:bg-red-100 transition-colors text-red-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: Competitions (2/3 width) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/items/scroll.png') }}" alt="" class="w-5 h-5 object-contain">
                        <h3 class="font-bold text-slate-900">Event ({{ $tournament->competitions->count() }})</h3>
                    </div>
                </div>

                @if($tournament->competitions->isEmpty())
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada event.</div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($tournament->competitions as $c)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <strong class="text-slate-800 text-sm">{{ $c->reveal_order }}. {{ $c->name }}</strong>
                                @php
                                    $cs = ['hidden'=>'bg-slate-100 text-slate-400','revealed'=>'bg-sky-100 text-sky-700','registration_open'=>'bg-emerald-100 text-emerald-700','registration_closed'=>'bg-amber-100 text-amber-700','ongoing'=>'bg-indigo-100 text-indigo-700','finished'=>'bg-slate-100 text-slate-400'];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $cs[$c->status] ?? 'bg-slate-100 text-slate-400' }}">
                                    {{ str_replace('_',' ',$c->status) }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-3 text-xs text-slate-400 mb-3">
                                <span class="flex items-center gap-1">
                                    <img src="{{ asset('images/items/gems.png') }}" alt="" class="w-3.5 h-3.5 object-contain">
                                    {{ $c->slot_count ?? $c->slot_total }} peserta
                                </span>
                                @if($c->has_registration)
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <img src="{{ asset('images/items/scroll.png') }}" alt="" class="w-3.5 h-3.5 object-contain">
                                        Pendaftaran publik
                                    </span>
                                @else
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Tanpa pendaftaran
                                    </span>
                                @endif
                                @if($c->bracket_format)
                                    @php
                                        $fmtOpt = \App\Services\Bracket\FixedBracketOptionsResolver::getOption($c->slot_count, $c->bracket_format);
                                        $fmtLabel = $fmtOpt ? $fmtOpt['label'] : $c->bracket_format;
                                    @endphp
                                    <span class="flex items-center gap-1 text-indigo-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                        {{ $fmtLabel }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.competitions.edit', [$tournament, $c]) }}"
                                   class="px-3 py-1.5 text-xs font-bold border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.competitions.destroy', [$tournament, $c]) }}"
                                      @submit.prevent="$store.modal.show('Hapus event ini?', () => $el.submit(), 'Konfirmasi Hapus', 'Ya, Hapus', 'danger')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 text-xs font-bold bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition-colors">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add Competition Form --}}
                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50" x-data="eventCreateForm">
                    <h4 class="font-bold text-slate-700 text-sm mb-4">+ Tambah Event</h4>
                    <form method="POST" action="{{ route('admin.competitions.store', $tournament) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Nama Event *</label>
                            <input type="text" name="name" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 bg-white" placeholder="Contoh: Parkour Challenge" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Detail / Deskripsi</label>
                            <textarea name="detail" rows="2" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 bg-white resize-none" placeholder="Aturan dan info event..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Foto Event (jpg/png, max 2MB)</label>
                            <input type="file" name="photo" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white" accept="image/*" @change="handleImageUpload" x-ref="photoInput">
                            
                            <template x-if="cropPreview">
                                <div class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 shadow-sm inline-block">
                                    <img :src="cropPreview" class="h-32 w-auto object-cover" alt="Cropped preview">
                                    <button type="button" @click="clearCrop()" class="absolute top-2 right-2 bg-white/90 text-red-500 hover:bg-red-50 p-1.5 rounded-lg shadow-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Urutan Reveal *</label>
                                <input type="number" name="reveal_order" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 bg-white" value="{{ $tournament->competitions->count() + 1 }}" min="1" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Jumlah Slot *</label>
                                <select name="slot_count" id="slotCountSelect" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 bg-white" required onchange="updateBracketOptions()">
                                    @foreach([16, 24, 32] as $n)
                                        <option value="{{ $n }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="bracketFormatGroup">
                            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">Format Bracket *</label>
                            <select name="bracket_format" id="bracketFormatSelect" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 bg-white" required>
                                <!-- Populated by JS -->
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="hidden" name="has_bracket" value="0">
                                <input type="checkbox" name="has_bracket" id="hasBracketCheck" value="1" checked
                                       onchange="toggleBracketFormat()"
                                       class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                <span class="text-slate-700 font-medium">Gunakan Bracket</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="hidden" name="has_registration" value="0">
                                <input type="checkbox" name="has_registration" value="1"
                                       class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                <span class="text-slate-700 font-medium">Pendaftaran Publik</span>
                            </label>
                        </div>
                        <button type="submit"
                                class="px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-bold rounded-xl transition-colors">
                            + Tambah Event
                        </button>
                    </form>
                    
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
            </div>
        </div>

        @php
            $bracketOptionsMap = [];
            foreach([16, 24, 32] as $sc) {
                $bracketOptionsMap[$sc] = \App\Services\Bracket\FixedBracketOptionsResolver::getOptions($sc);
            }
        @endphp

        {{-- RIGHT: Host Key + Info (1/3 width) --}}
        <div class="space-y-4">
            {{-- Host Key --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('images/items/trophy.png') }}" alt="" class="w-5 h-5 object-contain">
                    <h3 class="font-bold text-slate-900">Host Key</h3>
                </div>
                @if($activeKey)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4" x-data="{ copied: false }">
                        <div class="flex items-center justify-between mb-1">
                            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Key Aktif</div>
                            <button @click="navigator.clipboard.writeText('{{ $activeKey->key_hash }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-md transition-colors"
                                    :class="copied ? 'bg-emerald-200 text-emerald-800' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'">
                                <svg x-show="!copied" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-cloak x-show="copied" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>
                        <div class="font-mono text-sm font-bold text-emerald-700 break-all mb-2 select-all">{{ $activeKey->key_hash }}</div>
                        <div class="text-xs text-slate-400">Dibuat: {{ $activeKey->created_at?->format('d M Y H:i') }}</div>
                        @if($activeKey->used_at)
                            <div class="text-xs text-slate-400">Terpakai: {{ $activeKey->used_at->format('d M Y H:i') }}</div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.host-key.generate', $tournament) }}" @submit.prevent="$store.modal.show('Generate key baru akan mencabut key lama. Lanjutkan?', () => $el.submit(), 'Konfirmasi Generate', 'Ya, Generate', 'warning')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Generate Ulang
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.host-key.revoke', [$tournament, $activeKey]) }}" @submit.prevent="$store.modal.show('Cabut key ini?', () => $el.submit(), 'Konfirmasi Cabut', 'Ya, Cabut', 'danger')">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-bold bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition-colors">
                                Cabut Key
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-slate-400 text-sm mb-4">Belum ada host key aktif.</p>
                    <form method="POST" action="{{ route('admin.host-key.generate', $tournament) }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors">
                            <img src="{{ asset('images/items/trophy.png') }}" alt="" class="w-4 h-4 object-contain">
                            Generate Host Key
                        </button>
                    </form>
                @endif
            </div>


            {{-- Public Link --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <h3 class="font-bold text-slate-900">Public Link</h3>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono text-xs text-slate-600 break-all select-all">
                    {{ route('public.tournament.show', $tournament) }}
                </div>
                <p class="text-xs text-slate-400 mt-2">
                    @if($tournament->is_visible)
                        <span class="text-emerald-600 font-medium">Accessible oleh publik</span>
                    @else
                        Tidak bisa diakses (tournament hidden)
                    @endif
                </p>
                @if($tournament->is_visible)
                <a href="{{ route('public.tournament.show', $tournament) }}" target="_blank"
                   class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-sky-600 hover:text-sky-800 transition-colors">
                    Buka Public Page
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @endif
            </div>

            {{-- Host Panel Link --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('images/items/gems.png') }}" alt="" class="w-5 h-5 object-contain">
                    <h3 class="font-bold text-slate-900">Host Panel</h3>
                </div>
                <a href="{{ route('host.login') }}" target="_blank"
                   class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Host Panel
                </a>
                <p class="text-xs text-slate-400 mt-2 text-center">Gunakan host key di atas untuk login.</p>
            </div>
        </div>
    </div>
</div>

<script>
const bracketMap = @json($bracketOptionsMap);
const oldFormat = "{{ old('bracket_format') }}";

function updateBracketOptions() {
    const slotSelect = document.getElementById('slotCountSelect');
    const formatSelect = document.getElementById('bracketFormatSelect');
    const currentSlot = slotSelect.value;
    const options = bracketMap[currentSlot] || [];

    formatSelect.innerHTML = '';
    options.forEach(opt => {
        const el = document.createElement('option');
        el.value = opt.code;
        el.textContent = opt.label;
        if (opt.code === oldFormat) el.selected = true;
        formatSelect.appendChild(el);
    });
}

function toggleBracketFormat() {
    const check = document.getElementById('hasBracketCheck');
    const group = document.getElementById('bracketFormatGroup');
    const select = document.getElementById('bracketFormatSelect');
    if (check.checked) {
        group.style.display = 'block';
        select.setAttribute('required', 'required');
    } else {
        group.style.display = 'none';
        select.removeAttribute('required');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateBracketOptions();
});
</script>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventCreateForm', () => ({
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
                    const img = document.getElementById('cropperImageCreate');
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
@endsection
