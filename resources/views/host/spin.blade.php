@extends('layouts.host')
@section('title', 'Spin Wheel — ' . $competition->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/spin-wheel.css') }}">
<style>
    /* Restore missing variables & classes for Spin Wheel */
    :root {
        --bg: #f8fafc;
        --card: #ffffff;
        --border: #e2e8f0;
        --accent: #3b82f6;
        --accent2: #00d4aa;
        --danger: #ef4444;
        --muted: #64748b;
    }
    .dark {
        --bg: #0b0f19;
        --card: #151b2b;
        --border: #1c2438;
        --muted: #94a3b8;
    }
    .card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 14px;
    }
    .btn-ghost { background: transparent; color: var(--muted); }
    .btn-ghost:hover { background: rgba(0,0,0,0.05); color: var(--accent); }
    .dark .btn-ghost:hover { background: rgba(255,255,255,0.05); }
    
    .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
    .btn-danger:hover { background: rgba(239,68,68,0.2); }
    .btn-success { 
        background: linear-gradient(135deg, #10b981, #059669); 
        color: #ffffff; 
        font-weight: 800;
        border: 1px solid rgba(16, 185, 129, 0.8);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-success:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5); 
        background: linear-gradient(135deg, #34d399, #10b981);
    }

    .btn-primary { background: var(--accent); color: #fff; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.3); }

    .form-control {
        background: var(--bg);
        border: 1px solid var(--border);
        color: inherit;
        padding: 10px 14px;
        border-radius: 8px;
        font-family: inherit;
        outline: none;
    }
    .form-control:focus { border-color: var(--accent); }
    .page-title { font-size: 24px; font-weight: 800; display: flex; align-items: center; gap: 8px; }
    .muted { color: var(--muted); }
</style>
@endpush

@section('content')
    <div class="flex justify-between items-center" style="margin-bottom:24px;">
        <div>
            <h1 class="page-title">
                <img src="{{ asset('images/items/roulette.png') }}" alt="Roulette" class="w-8 h-8 object-contain">
                Spin Wheel
            </h1>
            <p class="muted">{{ $competition->name }} - Pilih {{ $competition->effectiveSlotCount() }} peserta</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('host.timeline') }}" class="btn btn-ghost btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Timeline
            </a>
            @if(count($selected) > 0)
                <form method="POST" action="{{ route('host.spin.reset', $competition) }}" x-data
                      @submit.prevent="$store.modal.show('Reset spin wheel? Semua pilihan akan diulang.', () => $el.submit(), 'Konfirmasi', 'Ya, Reset', 'danger')">
                    @csrf
                    <button class="btn btn-danger btn-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </button>
                </form>
            @endif
            @if(count($selected) > 0)
                <form method="POST" action="{{ route('host.spin.generate-bracket', $competition) }}">
                    @csrf
                    <button class="btn btn-success btn-sm flex items-center gap-2">
                        @if($competition->has_bracket)
                            <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 object-contain" alt="">
                            Buat Bagan ({{ count($selected) }} peserta)
                        @else
                            <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 object-contain" alt="">
                            Selesaikan Spin & Mulai Lomba
                        @endif
                    </button>
                </form>
            @endif
        </div>
    </div>

<div class="spin-layout">
    {{-- Wheel section --}}
    <div class="card" style="display:flex;flex-direction:column;align-items:center;gap:24px;padding:32px;">
        <div class="wheel-container">
            <svg id="spin-wheel" width="380" height="380" viewBox="-190 -190 380 380">
                <g id="wheel-slices"></g>
            </svg>
            <div class="wheel-arrow"></div>
            <div class="wheel-center"></div>
        </div>

        <div class="spin-controls">
            <button class="btn-spin btn-spin-one flex items-center justify-center gap-2" id="btn-spin-one" {{ $isFull ? 'disabled' : '' }}>
                <img src="{{ asset('images/items/dice.png') }}" alt="Spin" class="w-5 h-5 object-contain"> Spin 1x
            </button>
            <button class="btn-spin btn-spin-all flex items-center justify-center gap-2" id="btn-spin-all" {{ $isFull ? 'disabled' : '' }}>
                <img src="{{ asset('images/items/roulette.png') }}" alt="Spin All" class="w-5 h-5 object-contain"> Spin All
            </button>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;width:100%;">
            <div class="pool-stat">
                <div class="pool-stat-value" id="pool-count">{{ count($pool) }}</div>
                <div class="pool-stat-label">Sisa di Pool</div>
            </div>
            <div class="pool-stat">
                <div class="pool-stat-value" id="selected-count">{{ count($selected) }}</div>
                <div class="pool-stat-label">Terpilih / {{ $competition->effectiveSlotCount() }}</div>
            </div>
        </div>
    </div>

    {{-- Selected panel --}}
    <div class="card p-6 flex flex-col gap-4">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;">
                <img src="{{ asset('images/items/guestbook.png') }}" class="w-5 h-5 object-contain" alt="">
                Terpilih (<span id="selected-count-title">{{ count($selected) }}</span>/{{ $competition->effectiveSlotCount() }})
            </h3>
        </div>
        
        <div style="display:flex; gap:8px;">
            <input type="text" id="manual-name" class="form-control text-sm" style="flex:1;" placeholder="Nama peserta manual..." onkeypress="if(event.key === 'Enter') addManualName()">
            <button class="btn btn-primary btn-sm flex items-center gap-1" onclick="addManualName()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </button>
        </div>

        <div class="selected-list" id="selected-list">
            @foreach($selected as $i => $name)
            <div class="selected-item" style="display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="selected-num">{{ $i + 1 }}</div>
                    <span>{{ $name }}</span>
                </div>
                <button class="btn btn-ghost btn-sm" style="padding:4px; border:none; color:var(--danger);" onclick="removeSelectedName('{{ $name }}')" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endforeach
        </div>

        @if($isFull)
            <div style="padding:12px;background:rgba(46,213,115,.1);border:1px solid rgba(46,213,115,.3);border-radius:8px;display:flex;align-items:center;justify-content:center;gap:8px;color:var(--success);font-weight:600;font-size:14px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Slot penuh! Klik "Buat Bagan" di atas.
            </div>
        @endif
    </div>
</div>

{{-- Drawn toast / Modal --}}
<div id="drawn-toast" class="spin-modal-overlay">
    <div class="spin-modal flex flex-col items-center card p-8" style="background: var(--card); border: 1px solid var(--border); border-radius: 24px;">
        <img src="{{ asset('images/items/party_popper.png') }}" class="w-16 h-16 object-contain mb-4" alt="">
        <h2 style="font-size:24px; font-weight:800; margin-bottom:8px;">Selamat!</h2>
        <div id="drawn-name" style="font-size:32px; font-weight:900; color:var(--accent); margin-bottom:24px; word-break:break-all;"></div>
        
        <div style="display:flex; gap:12px; justify-content:center;">
            <button class="btn btn-ghost" onclick="cancelSpin()" style="padding:10px 24px;">Batalkan</button>
            <button class="btn btn-success flex items-center gap-2" onclick="acceptSpin()" style="padding:10px 24px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Lanjutkan
            </button>
        </div>
    </div>
</div>

<style>
.spin-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}
.spin-modal-overlay.show {
    opacity: 1;
    pointer-events: auto;
}
.spin-modal {
    background: var(--card);
    padding: 40px;
    border-radius: 20px;
    border: 1px solid var(--border);
    text-align: center;
    transform: scale(0.9);
    transition: transform 0.3s;
    min-width: 300px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
}
.spin-modal-overlay.show .spin-modal {
    transform: scale(1);
}
</style>

@endsection

@push('scripts')
<script>
window.CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
window.spinPool = @json($pool);
window.spinSelected = @json($selected);
window.spinSlotTotal = {{ $competition->effectiveSlotCount() }};
window.spinDrawUrl = `{{ route('host.spin.draw', $competition) }}`;
window.spinDrawAllUrl = `{{ route('host.spin.draw-all', $competition) }}`;
window.spinAcceptUrl = `{{ route('host.spin.accept-spin', $competition) }}`;
window.spinAddManualUrl = `{{ route('host.spin.add-manual', $competition) }}`;
window.spinRemoveUrl = `{{ route('host.spin.remove-selected', $competition) }}`;
</script>
<script src="{{ asset('js/spin-wheel.js') }}"></script>
@endpush
