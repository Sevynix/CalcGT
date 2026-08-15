@extends('layouts.host')
@section('title', 'Kelola Peserta - ' . $competition->name)

@section('content')
<div class="max-w-2xl mx-auto py-6 space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('host.timeline') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition-colors mb-2">
                ← Kembali ke Timeline
            </a>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <img src="{{ asset('images/items/pencil.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Kelola Peserta
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                {{ $competition->name }} - <strong class="text-slate-700 dark:text-slate-200">{{ $competition->effectiveSlotCount() }} Peserta</strong>
            </p>
        </div>
    </div>

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="p-4 rounded-xl text-sm font-medium bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800/60 flex items-center gap-2">
            <span><img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="">️</span> {{ session('error') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 rounded-2xl p-6 shadow-sm space-y-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Input Nama Peserta</h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 leading-relaxed">
                Masukkan nama peserta (satu per baris). Nama ini akan langsung diundi ke dalam bagan pertandingan saat Anda mengklik "Buat Bagan & Mulai".
            </p>
        </div>

        <textarea id="manualInput" rows="5"
                  class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3.5 text-sm text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                  placeholder="Masukkan nama peserta, satu per baris..."></textarea>
        
        <div class="flex justify-between items-center pt-1">
            <span class="text-xs text-slate-400 font-semibold">Total Peserta Saat Ini: <strong class="text-brand-600 dark:text-brand-400" id="totalCount">{{ count($participants) }}</strong></span>
            <button type="button" onclick="addManual()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shadow-sm cursor-pointer">
                + Tambahkan Peserta
            </button>
        </div>

        {{-- Participant Tag List --}}
        <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Peserta ({{ count($participants) }})</p>
            
            @if(count($participants) === 0)
                <p class="text-xs text-slate-400 italic py-2 text-center">Belum ada peserta ditambahkan.</p>
            @else
                <div class="flex flex-wrap gap-2" id="tagList">
                    @foreach($participants as $mp)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-full text-xs font-bold">
                            {{ $mp->name }}
                            <button type="button" onclick="removeManual({{ $mp->id }})" class="text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 transition-colors font-black text-sm leading-none cursor-pointer">
                                &times;
                            </button>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Generate Bracket Button --}}
    <div class="pt-2">
        <form method="POST" action="{{ route('host.manual-participants.generate-bracket', $competition) }}">
            @csrf
            <button type="submit" class="w-full py-3 px-6 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-sm font-extrabold rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <span><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span> Buat Bagan & Mulai Pertandingan (<span id="btnParticipantCount">{{ count($participants) }}</span> Peserta)
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addManual() {
    const textarea = document.getElementById('manualInput');
    const names = textarea.value.trim().split('\n').map(n => n.trim()).filter(n => n);
    
    if (names.length === 0) return;

    fetch('{{ route("host.manual-participants.add", $competition) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ names }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else if (data.error) {
            showErrorModal(data.error);
        }
    });
}

let participantToDelete = null;

function removeManual(id) {
    participantToDelete = id;
    const modal = document.getElementById('custom-delete-modal');
    modal.classList.remove('hidden');
    // slight delay for animation
    setTimeout(() => {
        modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
        modal.firstElementChild.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    participantToDelete = null;
    const modal = document.getElementById('custom-delete-modal');
    modal.firstElementChild.classList.remove('scale-100', 'opacity-100');
    modal.firstElementChild.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function confirmDeleteManual() {
    if (!participantToDelete) return;
    
    fetch(`{{ url('host/competitions/' . $competition->id . '/manual-participants') }}/${participantToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}

function showErrorModal(message) {
    document.getElementById('error-message-text').innerText = message;
    const modal = document.getElementById('custom-error-modal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
        modal.firstElementChild.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeErrorModal() {
    const modal = document.getElementById('custom-error-modal');
    modal.firstElementChild.classList.remove('scale-100', 'opacity-100');
    modal.firstElementChild.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>

{{-- Custom Delete Modal --}}
<div id="custom-delete-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 opacity-0 transition-all duration-300">
        <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700/50 flex items-center justify-center mx-auto mb-5 text-red-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight mb-2">
            Hapus Peserta?
        </h3>
        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
            Apakah Anda yakin ingin menghapus peserta ini dari daftar? Aksi ini tidak dapat dibatalkan.
        </p>

        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-extrabold text-sm rounded-xl transition-all cursor-pointer">
                Batal
            </button>
            <button onclick="confirmDeleteManual()" class="flex-1 py-3 px-4 bg-transparent border border-red-600 dark:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-500 font-extrabold text-sm rounded-xl transition-all cursor-pointer">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

{{-- Custom Error Modal --}}
<div id="custom-error-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 opacity-0 transition-all duration-300">
        <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700/50 flex items-center justify-center mx-auto mb-5 text-red-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight mb-2">
            Gagal Menambahkan
        </h3>
        <p id="error-message-text" class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
            <!-- Error message goes here -->
        </p>

        <div class="flex gap-3">
            <button onclick="closeErrorModal()" class="flex-1 py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm rounded-xl shadow-md transition-all cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endpush

