@extends('layouts.host')
@section('title', 'Bracket - ' . $competition->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bracket.css') }}?v={{ filemtime(public_path('css/bracket.css')) }}">
<style>
/* ── Page ──────────────────────────────────────────────────── */
.bracket-page { padding: 12px 0 40px; }
.bracket-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; flex-wrap:wrap; gap:8px; }
.bracket-title { font-size:22px; font-weight:800; color:var(--text); }
.bracket-sub   { font-size:13px; color:var(--muted); margin-top:2px; }

/* ── Phase Sections ──────────────────────────────────────────── */
.phase-section {
    margin-bottom: 20px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}
.phase-header {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
}
.phase-title { font-size: 15px; font-weight: 700; }
.phase-body  { padding: 16px; overflow-x: auto; }

/* ── Bracket Side Labels ────────────────────────────────────── */
.bracket-side-label {
    font-size: 11px; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--muted);
    margin: 20px 0 10px;
    display: flex; align-items: center; gap: 8px;
}
.bracket-side-label::before, .bracket-side-label::after {
    content:''; flex:1; height:1px; background:var(--border);
}
.upper-label  { color: var(--accent); }
.lower-label  { color: #e88b2e; }
.gf-label     { color: var(--accent2); }

/* ── Next Phase Button ──────────────────────────────────────── */
.next-phase-banner {
    background: linear-gradient(135deg, rgba(0,212,170,.1), rgba(108,99,255,.1));
    border: 1px solid rgba(0,212,170,.3);
    border-radius: 14px;
    padding: 20px 24px;
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
}
.next-phase-info h3 { font-size: 16px; font-weight: 700; color: var(--accent2); margin-bottom: 4px; }
.next-phase-info p  { font-size: 13px; color: var(--muted); }

/* ── FFA Phase ──────────────────────────────────────────────── */
.ffa-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px; }
.ffa-card {
    padding: 14px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    text-align: center;
    font-size: 13px; font-weight: 600;
    transition: all .2s;
}
.ffa-card.passed { border-color: var(--success); background: rgba(46,213,115,.06); color: var(--success); }

/* ── Bracket Match ──────────────────────────────────────────── */
.bracket-round { display:flex; flex-direction:column; gap:16px; min-width:160px; }
.match-wrapper { position:relative; }
.match {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    transition: box-shadow .2s;
    min-width: 160px;
}
.match:hover { box-shadow: 0 4px 16px rgba(0,0,0,.25); }
.match-slot {
    display: flex; align-items: center; justify-content: space-between;
    padding: 9px 12px; gap: 8px;
    font-size: 13px; font-weight: 600;
    border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background .15s;
}
.match-slot:last-child { border-bottom: none; }
.match-slot:hover { background: rgba(255,255,255,.04); }
.match-slot.winner { background: rgba(0,212,170,.08); color: var(--accent2); }
.match-slot.loser  { opacity: .5; }
.slot-name { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.slot-empty { color: var(--muted); font-style: italic; font-weight: 400; }

.match-locked { opacity:.55; pointer-events:none; }
.match-bye { opacity:.7; }
.match-if-necessary { border-style: dashed; opacity:.7; }
.match-if-necessary .match-slot { cursor:default; }

.round-label { font-size:11px; color:var(--muted); text-align:center; margin-bottom:8px; font-weight:700; text-transform:uppercase; }

/* ── Bracket columns layout ────────────────────────────────── */
.bracket-columns { display:flex; gap:40px; align-items:flex-start; }
.bracket-col { display:flex; flex-direction:column; align-items:center; }

/* ── Status badges ──────────────────────────────────────────── */
.status-badge {
    display:inline-flex; align-items:center; padding:3px 10px;
    border-radius:999px; font-size:10px; font-weight:700; text-transform:uppercase;
}
.s-ongoing  { background:rgba(108,99,255,.15); color:var(--accent); }
.s-finished { background:rgba(123,130,160,.15); color:var(--muted); }
.s-pending  { background:rgba(255,165,2,.12); color:var(--warning); }

.btn { display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;border:none;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;font-family:inherit; }
.btn-primary { background:var(--accent);color:#fff; }
.btn-primary:hover { background:#5b52e0; }
.btn-ghost   { background:transparent;color:var(--muted);border:1px solid var(--border); }
.btn-ghost:hover { background:var(--border);color:var(--text); }
.btn-danger  { background:var(--danger);color:#fff; }
.btn-success { background:var(--success);color:#000; }
.btn-warning { background:var(--warning);color:#000; }
.btn-sm { padding:5px 12px; font-size:12px; }

.alert { padding:12px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; }
.alert-success { background:rgba(46,213,115,.12); color:var(--success); border:1px solid rgba(46,213,115,.2); }
.alert-error   { background:rgba(255,71,87,.12); color:var(--danger); border:1px solid rgba(255,71,87,.2); }
</style>
@endpush

@section('content')
<div class="bracket-page">
    <div class="bracket-header">
        <div>
            <h1 class="bracket-title"><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> {{ $competition->name }}</h1>
            <p class="bracket-sub">
                {{ $competition->effectiveSlotCount() }} peserta
            </p>
        </div>
        </div>
    </div>

    @php
        $finalWinner = null;
        if (!empty($bracketDatas)) {
            $lastData = end($bracketDatas);
            if (($lastData['status'] ?? '') === 'finished' || ($competition->status ?? '') === 'finished') {
                if (in_array($lastData['bracket_type'] ?? '', ['upper_lower_4', 'upper_lower_6'])) {
                    $gf = $lastData['grand_final'] ?? [];
                    if (!empty($gf)) {
                        if (!empty($gf[1]['winner'])) {
                            $finalWinner = $gf[1]['winner'];
                        } elseif (!empty($gf[0]['winner'])) {
                            $finalWinner = $gf[0]['winner'];
                        }
                    }
                } elseif (($lastData['bracket_type'] ?? '') === 'single_elim') {
                    $rnds = $lastData['rounds'] ?? [];
                    if (!empty($rnds)) {
                        $maxR = max(array_keys($rnds));
                        if (!empty($rnds[$maxR][0]['winner'])) {
                            $finalWinner = $rnds[$maxR][0]['winner'];
                        }
                    }
                }
            }
        }
    @endphp

    @if($finalWinner)
    <div class="winner-banner-wrapper">
        <div class="winner-banner">
            <div class="winner-glow-pulse"></div>
            <div class="winner-confetti"></div>
            
            <div class="winner-avatar-trophy" style="animation: avatar-bounce 1s 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; z-index: 2;">
                <img src="{{ asset('images/items/gold_trophy.png') }}" class="w-24 h-24 object-contain" alt="Gold Trophy">
            </div>

            <div class="winner-content">
                <img src="{{ asset('images/items/party_popper.png') }}" class="w-10 h-10 object-contain winner-icon" alt="Party Popper">
                <h2>JUARA: {{ $finalWinner }}</h2>
                <p>Telah memenangkan turnamen!</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Next Phase Banner removed (now auto-starts) --}}

    {{-- If no phases yet --}}
    @if(empty($bracketDatas))
        <div style="text-align:center;padding:60px;background:var(--card);border:1px dashed var(--border);border-radius:16px;">
            <div style="font-size:48px;margin-bottom:16px;"><img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></div>
            <h3 style="font-size:18px;font-weight:700;margin-bottom:8px;">Bracket Belum Dibuat</h3>
            <p style="color:var(--muted);margin-bottom:20px;">Pergi ke halaman Timeline untuk memulai proses pembentukan bracket.</p>
            <a href="{{ route('host.timeline') }}" class="btn btn-primary">Kembali ke Timeline</a>
        </div>
    @endif

    {{-- Phases --}}
    @foreach($bracketDatas as $i => $data)
    @php
        $phase = $phases[$i];
        $phaseLabels = [
            'single_elim' => 'Single Elimination',
            'upper_lower_4' => 'Upper + Lower Bracket (4 Finalis)',
            'upper_lower_6' => 'Upper + Lower Bracket (6 Finalis)',
        ];
        $phaseLabel = $phaseLabels[$data['bracket_type']] ?? $data['bracket_type'];
    @endphp

    <div class="phase-section">
        <div class="phase-header">
            <div>
                @if($phase->survivor_count)
                    <span style="font-size:12px;color:var(--muted);">Top {{ $phase->survivor_count }} lolos</span>
                @endif
            </div>
            <span class="status-badge s-{{ $data['status'] }}">{{ $data['status'] === 'finished' ? 'Selesai' : ($data['status'] === 'ongoing' ? 'Berlangsung' : 'Menunggu') }}</span>
        </div>

        <div class="phase-body">

            @if($data['status'] === 'pending')
                <div class="alert alert-info" style="margin-bottom:16px;">
                    ⏳ <strong>Fase Belum Dimulai:</strong> Selesaikan seluruh pertandingan pada fase sebelumnya terlebih dahulu untuk memilih semifinalis dan memulai fase ini.
                </div>
            @endif



            {{-- ── Bracket Content ────────────────────────── --}}
            @if(in_array($data['bracket_type'], ['upper_lower_4', 'upper_lower_6']))
                @include('host._upper_lower_bracket', ['data' => $data, 'competition' => $competition, 'isPublic' => false, 'bracketType' => $data['bracket_type'], 'isSeeding' => $phase->status === 'seeding'])
            @elseif($data['bracket_type'] === 'round_robin')
                @include('host._group_stage', ['data' => $data, 'isPublic' => false, 'bracketType' => $data['bracket_type'], 'isSeeding' => $phase->status === 'seeding'])
            @elseif($data['bracket_type'] === 'ffa')
                @include('host._ffa_grid', ['data' => $data, 'competition' => $competition, 'isPublic' => false, 'bracketType' => $data['bracket_type'], 'isSeeding' => $phase->status === 'seeding'])
            @else
                @include('host._single_elim', ['data' => $data, 'competition' => $competition, 'isPublic' => false, 'bracketType' => $data['bracket_type'], 'isSeeding' => $phase->status === 'seeding'])
            @endif

        </div>
    </div>
    @endforeach

    {{-- Placeholder for unstarted phases removed --}}
</div>

{{-- Winner Modal --}}
<div id="winnerModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;padding:28px;min-width:300px;max-width:400px;width:90%;">
        <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;"><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Pilih Pemenang</h3>
        <div id="winnerChoices" style="display:flex;flex-direction:column;gap:10px;"></div>
        <button onclick="closeWinnerModal()" style="margin-top:16px;width:100%;padding:10px;background:transparent;border:1px solid var(--border);border-radius:8px;color:var(--muted);cursor:pointer;font-size:13px;">Batal</button>
    </div>
</div>

{{-- Assign Modal for Seeding --}}
<div id="assignModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;padding:28px;min-width:300px;max-width:400px;width:90%;">
        <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;"><img src="{{ asset('images/items/pencil.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Pilih Peserta</h3>
        <select id="assignSelect" class="form-control" style="width:100%; margin-bottom:16px; background:var(--bg); border:1px solid var(--border); color:var(--text); padding:10px; border-radius:8px;">
        </select>
        <button onclick="submitAssign()" class="btn btn-primary" style="width:100%; justify-content:center;">Pilih</button>
        <button onclick="closeAssignModal()" style="margin-top:10px;width:100%;padding:10px;background:transparent;border:1px solid var(--border);border-radius:8px;color:var(--muted);cursor:pointer;font-size:13px;">Batal</button>
    </div>
</div>

<script>
function checkQuickSeedLimit(checkbox) {
    let checked = document.querySelectorAll('input[name="semifinalists[]"]:checked');
    const btn = document.getElementById('btnQuickSeedSubmit');
    if (checked.length > 2) {
        checkbox.checked = false;
        checked = document.querySelectorAll('input[name="semifinalists[]"]:checked');
    }

    document.querySelectorAll('input[name="semifinalists[]"]').forEach(cb => {
        const label = cb.closest('label');
        if (cb.checked) {
            label.style.background = '#0e59c3';
            label.style.borderColor = '#0e59c3';
            label.style.color = '#fff';
        } else {
            label.style.background = 'rgba(128,128,128,0.1)';
            label.style.borderColor = 'rgba(128,128,128,0.2)';
            label.style.color = 'inherit';
        }
    });

    if (btn) {
        btn.disabled = checked.length !== 2;
    }
}

let currentNodeId = null;
let currentMode   = 'set'; // set | correct | assign
let currentSlot   = null;

window.unassignedParticipants = @json($unassigned ?? []);

function openWinnerModal(nodeId, pa, pb, mode) {
    currentNodeId = nodeId;
    currentMode = mode;
    const choices = document.getElementById('winnerChoices');
    choices.innerHTML = '';
    
    if (pa && pa !== 'null') {
        const btnA = document.createElement('button');
        btnA.className = 'btn btn-primary';
        btnA.textContent = pa;
        btnA.onclick = () => { closeWinnerModal(); submitWinner(pa); };
        choices.appendChild(btnA);
    }
    
    if (pb && pb !== 'null') {
        const btnB = document.createElement('button');
        btnB.className = 'btn btn-primary';
        btnB.textContent = pb;
        btnB.onclick = () => { closeWinnerModal(); submitWinner(pb); };
        choices.appendChild(btnB);
    }
    
    if (mode === 'correct') {
        const btnReset = document.createElement('button');
        const emptyNode = (pa === 'null' && pb === 'null');
        
        btnReset.className = 'btn btn-danger';
        btnReset.style.marginTop = '10px';
        btnReset.textContent = 'Batalkan Hasil (Reset)';
        btnReset.onclick = () => { 
            closeWinnerModal(); 
            openResetWinner(nodeId);
        };
        choices.appendChild(btnReset);
    }
    
    document.getElementById('winnerModal').style.display = 'flex';
}

function closeWinnerModal() {
    document.getElementById('winnerModal').style.display = 'none';
}

function openAssign(nodeId, slot) {
    if (window.unassignedParticipants.length === 0) {
        alert('Semua peserta sudah dimasukkan ke dalam bagan.');
        return;
    }
    currentNodeId = nodeId;
    currentSlot = slot;
    currentMode = 'assign';
    
    const sel = document.getElementById('assignSelect');
    sel.innerHTML = '<option value="">-- Pilih Peserta --</option>';
    window.unassignedParticipants.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p;
        opt.textContent = p;
        sel.appendChild(opt);
    });
    
    document.getElementById('assignModal').style.display = 'flex';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
}

function openSetWinner(nodeId, winner) {
    currentNodeId = nodeId;
    currentMode = 'set';
    submitWinner(winner);
}

function openCorrectWinner(nodeId, winner) {
    currentNodeId = nodeId;
    currentMode = 'correct';
    submitWinner(winner);
}

function openResetWinner(nodeId) {
    currentNodeId = nodeId;
    currentMode = 'reset';
    submitWinner('');
}

window.openSetWinner = openSetWinner;
window.openCorrectWinner = openCorrectWinner;
window.openResetWinner = openResetWinner;
window.openAssign = openAssign;

function submitAssign() {
    const participant = document.getElementById('assignSelect').value;
    if (!participant) return;
    
    fetch(`/host/bracket-node/${currentNodeId}/assign-participant`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ slot: currentSlot, participant }),
    })
    .then(async r => {
        let text = await r.text();
        try {
            let data = JSON.parse(text);
            if (data.error) alert('Error: ' + data.error);
            else location.reload();
        } catch(e) {
            alert('Error: ' + text);
        }
    })
    .catch(e => alert(e.message));
}

function submitWinner(winner) {
    let url = `/host/bracket-node/${currentNodeId}/set-winner`;
    if (currentMode === 'correct') url = `/host/bracket-node/${currentNodeId}/correct-winner`;
    if (currentMode === 'reset') url = `/host/bracket-node/${currentNodeId}/reset-match`;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ winner }),
    })
    .then(async r => {
        let text = await r.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch(e) {
            alert('Server error (Not JSON). Status: ' + r.status);
            console.error(text);
            saveScrollPos();
            location.reload();
            return;
        }
        if (data.error) {
            showCustomErrorModal('Pemenang Tidak Bisa Dikoreksi', data.error);
        } else {
            if (data.is_completed) {
                sessionStorage.setItem('bracketScrollY', 0);
                sessionStorage.setItem('bracketScrollX', 0);
            } else {
                saveScrollPos();
            }
            location.reload();
        }
    })
    .catch((err) => {
        showCustomErrorModal('Kesalahan Jaringan', err.message);
    });
}

function showCustomErrorModal(title, msg) {
    const modal = document.getElementById('custom-error-modal');
    const titleEl = document.getElementById('custom-error-title');
    const msgEl = document.getElementById('custom-error-msg');
    
    if (titleEl) titleEl.textContent = title || 'Pemenang Tidak Bisa Dikoreksi';
    if (msgEl) msgEl.textContent = msg || 'Match berikutnya sudah diputuskan atau node terkunci.';
    
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        alert((title ? title + ': ' : '') + msg);
    }
}

function closeCustomErrorModal() {
    const modal = document.getElementById('custom-error-modal');
    if (modal) modal.classList.add('hidden');
}

function saveScrollPos() {
    const wrap = document.querySelector('.bracket-wrap');
    if (wrap) sessionStorage.setItem('bracketScrollX', wrap.scrollLeft);
    sessionStorage.setItem('bracketScrollY', window.scrollY);
}

document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.querySelector('.bracket-wrap');
    const scrollX = sessionStorage.getItem('bracketScrollX');
    const scrollY = sessionStorage.getItem('bracketScrollY');
    
    if (wrap && scrollX !== null) {
        wrap.scrollLeft = parseInt(scrollX, 10);
        sessionStorage.removeItem('bracketScrollX');
    }
    if (scrollY !== null) {
        setTimeout(() => window.scrollTo(0, parseInt(scrollY, 10)), 50);
        sessionStorage.removeItem('bracketScrollY');
    }
});
</script>

{{-- Custom Error Modal --}}
<div id="custom-error-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl max-w-md w-full p-6 text-center transform scale-95 transition-all">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700/50 flex items-center justify-center mx-auto mb-4 text-2xl">
            <img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="">️
        </div>
        <h3 id="custom-error-title" class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-2">
            Pemenang Tidak Bisa Dikoreksi
        </h3>
        <p id="custom-error-msg" class="text-sm font-semibold text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
            Match berikutnya sudah diputuskan atau node terkunci.
        </p>

        <div class="bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-4 text-left mb-6">
            <div class="text-xs font-black uppercase tracking-wider text-amber-700 dark:text-amber-400 mb-2 flex items-center gap-1.5">
                <img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Cara Mengatasi / Solusi:
            </div>
            <ul class="text-xs font-medium text-amber-900 dark:text-amber-200 space-y-2 list-disc pl-4 leading-relaxed">
                <li>Buka match di babak selanjutnya yang terhubung dengan match ini, lalu <strong>Batalkan / Reset Pemenang</strong> pada match tersebut terlebih dahulu.</li>
                <li>Lakukan reset secara <strong>berurutan mundur</strong> dari babak teratas hingga babak ini.</li>
                <li>Setelah match selanjutnya di-reset, Anda akan dapat mengoreksi pemenang pada match ini.</li>
            </ul>
        </div>

        <button onclick="closeCustomErrorModal()" class="w-full py-3 px-5 bg-slate-900 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all cursor-pointer">
            Mengerti & Tutup
        </button>
    </div>
</div>
@endsection

