@extends('layouts.app')
@section('title', $tournament->name)
@section('meta_description', $tournament->description ?? 'Ikuti event di ' . $tournament->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bracket.css') }}?v={{ filemtime(public_path('css/bracket.css')) }}">
<style>
/* Timeline Layout Grid */
.timeline-section { padding: 36px 0 80px; }
.timeline-header { text-align: center; margin-bottom: 56px; }
.timeline { position: relative; max-width: 880px; margin: 0 auto; padding: 0 20px; }

/* Vertical Connecting Line */
.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 3px;
    transform: translateX(-50%);
    background: linear-gradient(to bottom,
        transparent 0%,
        #0e59c3 8%,
        #4b84e0 50%,
        #0e59c3 92%,
        transparent 100%
    );
}

.tl-row {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin-bottom: 64px;
    min-height: 160px;
}

/* Center Container (Dot + Floating Label) */
.tl-center {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.tl-dot {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #0e59c3;
    border: 4px solid #ffffff;
    box-shadow: 0 0 0 2px #0e59c3, 0 4px 14px rgba(14, 89, 195, 0.35);
    position: relative;
    transition: all 0.3s;
    flex-shrink: 0;
    z-index: 2;
}

html.dark .tl-dot {
    border-color: #0b0f19;
}

.tl-row.even .tl-dot {
    background: #0284c7;
    box-shadow: 0 0 0 2px #0284c7, 0 4px 14px rgba(2, 132, 199, 0.35);
}

.tl-dot.pulse::after {
    content: '';
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid #4b84e0;
    animation: pulseDot 2s infinite;
}

@keyframes pulseDot {
    0%   { opacity: 0.6; transform: scale(1); }
    70%  { opacity: 0; transform: scale(1.5); }
    100% { opacity: 0; transform: scale(1.5); }
}

.tl-label {
    position: absolute;
    bottom: calc(100% + 6px);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    white-space: nowrap;
    color: #0e59c3;
    background: #ffffff;
    padding: 3px 12px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

html.dark .tl-label {
    background: #151b2b;
    border-color: #2a3449;
    color: #60a5fa;
}

.tl-row.even .tl-label {
    bottom: auto;
    top: calc(100% + 6px);
    color: #0284c7;
}

html.dark .tl-row.even .tl-label {
    color: #38bdf8;
}

/* Connector Line */
.tl-connector {
    flex: 1;
    height: 2px;
    background: linear-gradient(to right, rgba(14, 89, 195, 0.35), transparent);
    max-width: 60px;
    flex-shrink: 0;
}

.tl-row.even .tl-connector {
    background: linear-gradient(to left, rgba(2, 132, 199, 0.35), transparent);
}

.tl-card-wrap {
    width: calc(50% - 80px);
    flex-shrink: 0;
}

.tl-spacer { width: calc(50% - 80px); flex-shrink: 0; }

.tl-row:not(.even) .tl-card-wrap { order: 2; }
.tl-row:not(.even) .tl-connector { order: 1; background: linear-gradient(to right, rgba(14,89,195,0.35), transparent); }
.tl-row:not(.even) .tl-center { order: 0; }

.tl-row.even .tl-spacer    { order: 2; }
.tl-row.even .tl-card-wrap { order: 0; }
.tl-row.even .tl-connector { order: 1; }

.live-dot { display:inline-block;width:7px;height:7px;border-radius:50%;background:#ffffff;margin-right:5px;animation:blink 1.2s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .timeline::before { left: 20px; transform: none; }
    .tl-row, .tl-row.even { flex-direction: column; align-items: flex-start; padding-left: 45px; margin-bottom: 40px; }
    .tl-center, .tl-row.even .tl-center { left: 20px; top: 0; transform: translateX(-50%); }
    .tl-label, .tl-row.even .tl-label { position: relative; bottom: auto; top: auto; margin-bottom: 4px !important; }
    .tl-spacer { display: none; }
    .tl-connector { display: none; }
    .tl-card-wrap, .tl-row.even .tl-card-wrap { width: 100%; order: 1 !important; }
}
</style>
@endpush

@section('content')
{{-- Hero --}}
<div class="py-10 border-b border-slate-200 dark:border-slate-800 relative overflow-hidden mb-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-slate-100 tracking-tight mb-3">{{ $tournament->name }}</h1>
            @if($tournament->description)
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base max-w-2xl leading-relaxed mb-6">{{ $tournament->description }}</p>
            @endif

            @if($tournament->banner_path)
                <div class="w-full max-w-xl mx-auto relative rounded-2xl overflow-hidden shadow-glass border border-slate-200 dark:border-slate-700/60 bg-white dark:bg-slate-800 group block {{ ($tournament->banner_aspect_ratio ?? '3:2') === '16:9' ? 'aspect-[16/9]' : 'aspect-[3/2]' }}">
                    <img src="{{ asset('storage/' . $tournament->banner_path) }}" loading="eager" fetchpriority="high" alt="{{ $tournament->name }} Banner" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02] block" draggable="false">
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Competitions Timeline --}}
<div class="timeline-section">
    <div class="max-w-6xl mx-auto px-4">
        <div class="timeline-header">
            <h2 class="text-3xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center justify-center gap-2.5">
                <img src="{{ asset('images/items/clock.png') }}" class="w-9 h-9 object-contain drop-shadow-md -translate-y-0.5" alt="Clock">
                Timeline Event
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm mt-2">Jadwal dan alur event ini secara berurutan</p>
        </div>

        @if($competitions->isEmpty())
            <div class="text-center p-12 bg-white dark:bg-slate-800/80 border border-dashed border-slate-200 dark:border-slate-700/80 rounded-3xl max-w-md mx-auto">
                <div class="text-5xl mb-4"><img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Belum Ada Event</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Nantikan pengumuman event selanjutnya!</p>
            </div>
        @else
            <div class="timeline" id="timeline">
                @php
                    $allLockImages = [
                        asset('images/lock/worldlock.png'),
                        asset('images/lock/diamondlock.png'),
                        asset('images/lock/bgl.png'),
                    ];
                @endphp

                @foreach($competitions as $i => $comp)
                @php
                    $isEven    = $i % 2 === 1;
                    $regCount  = $comp->registrations->count();
                    $slotCount = $comp->effectiveSlotCount();
                    $pct       = $comp->has_registration ? ($slotCount > 0 ? min(100, ($regCount / $slotCount) * 100) : 0) : 100;
                    $isActive  = in_array($comp->status, ['registration_open', 'ongoing']);
                    $barFillClass = match($comp->status) {
                        'registration_open'   => 'bg-gradient-to-r from-emerald-500 to-emerald-400',
                        'registration_closed' => 'bg-gradient-to-r from-amber-500 to-amber-400',
                        'ongoing'             => 'bg-gradient-to-r from-brand-600 to-brand-500',
                        'finished'            => 'bg-gradient-to-r from-emerald-500 to-emerald-400',
                        default               => 'bg-slate-400 dark:bg-slate-600',
                    };
                    $randomLockBg = $allLockImages[$i % count($allLockImages)];
                @endphp

                <div class="tl-row {{ $isEven ? 'even' : '' }}">
                    <div class="tl-spacer"></div>
                    <div class="tl-connector"></div>

                    {{-- Center: dot + floating label --}}
                    <div class="tl-center">
                        <div class="tl-label">Event {{ $comp->reveal_order }}</div>
                        <div class="tl-dot {{ $isActive ? 'pulse' : '' }}"></div>
                    </div>

                    <div class="tl-connector"></div>

                    {{-- Native GrowtopiaHelp Card --}}
                    <div class="tl-card-wrap">
                        <div class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white min-h-[160px] p-5 w-full scroll-mt-24" id="card-{{ $comp->id }}">
                            
                            <!-- Blurry Lock Background (Exact GrowtopiaHelp MSurg pattern) -->
                            <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none transition-opacity duration-300 z-0">
                                <img loading="lazy" src="{{ $randomLockBg }}" alt="" class="w-full h-full object-contain">
                            </div>

                            <div class="relative z-10 flex flex-col flex-1 gap-2">
                                <div class="cursor-pointer select-none" onclick="toggleCard({{ $comp->id }})">
                                    <div class="flex items-center justify-end">
                                        <span class="bg-slate-100 text-slate-800 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 shadow-sm">
                                            {{ $comp->status === 'hidden' ? 'HIDDEN' : str_replace('_', ' ', $comp->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <h2 class="text-lg font-black text-slate-800 tracking-tight leading-snug">
                                            {{ $comp->status === 'hidden' ? 'Belum Diungkap' : $comp->name }}
                                        </h2>
                                        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-1 max-w-[90%]">
                                            @if($comp->status === 'hidden')
                                                Detail event masih dirahasiakan oleh host. Nantikan pengumuman selanjutnya!
                                            @else
                                                {{ $comp->detail ? Str::limit($comp->detail, 85) : 'Kompetisi resmi tournament GrowtopiaHelp.' }}
                                            @endif
                                        </p>
                                    </div>

                                    @if($comp->status !== 'hidden' && $comp->photo_path)
                                    <div class="my-2 overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm">
                                        <img src="{{ asset('storage/' . $comp->photo_path) }}" class="w-full aspect-[16/9] object-cover" alt="{{ $comp->name }}">
                                    </div>
                                    @endif

                                    @if($comp->status !== 'hidden' && ($comp->has_registration || $comp->has_bracket))
                                    <div class="my-2">
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $barFillClass }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500 mt-1">
                                            <span>{{ $comp->effectiveSlotCount() }} Slot</span>
                                            @if($comp->has_registration)
                                                <span>{{ $regCount }} Terdaftar</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    @if($comp->status === 'hidden' || $comp->has_registration || $comp->has_bracket)
                                    <div class="flex items-center justify-between text-slate-800 font-bold text-sm pt-4 mt-auto border-t border-slate-100">
                                        <span>
                                            {{ $comp->status === 'hidden' ? 'Detail Event' : ($comp->status === 'registration_open' ? 'Daftar Event' : 'Lihat Detail') }}
                                        </span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                    @endif
                                </div>

                                {{-- Body --}}
                                <div class="hidden pt-2.5 mt-2.5 border-t border-slate-100 dark:border-slate-700/60" id="body-{{ $comp->id }}">
                                    @if($comp->status === 'hidden')
                                        <div class="text-center p-6 bg-brand-50 dark:bg-brand-900/20 rounded-2xl border border-brand-200 dark:border-brand-800/50">
                                            <img src="{{ asset('images/items/trophy.png') }}" class="w-12 h-12 mx-auto mb-3 drop-shadow-md" alt="Trophy">
                                            <div class="text-base text-brand-600 dark:text-brand-400 font-extrabold mb-1">Detail Event Masih Dirahasiakan</div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Nantikan saat host mengungkap detail event ini!</p>
                                        </div>
                                    @else
                                        @if($comp->has_registration)
                                            @if($comp->status === 'registration_open')
                                            <div class="mt-1">
                                                <div id="reg-wrap-{{ $comp->id }}">
                                                    <label class="block text-xs font-extrabold text-brand-600 dark:text-brand-400 mb-1.5 uppercase tracking-wider">GrowID kamu</label>
                                                    <div class="flex gap-2 items-center">
                                                        <input type="text" id="grow-{{ $comp->id }}" placeholder="Masukkan GrowID..." maxlength="18" class="flex-1 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-slate-800 dark:text-slate-100 text-sm outline-none focus:border-brand-500 transition-all">
                                                        <button class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs transition-all shrink-0 cursor-pointer" onclick="register({{ $comp->id }})"><img src="{{ asset('images/items/pencil.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="">️ Daftar</button>
                                                    </div>
                                                    <div id="reg-err-{{ $comp->id }}" class="hidden bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 p-3 rounded-xl text-xs mt-2"></div>
                                                </div>
                                            </div>
                                            @elseif(in_array($comp->status, ['registration_closed','ongoing']))
                                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-3 text-xs font-bold text-amber-600 dark:text-amber-400 mt-3.5"><svg class="w-4 h-4 inline-block translate-y-[-1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Pendaftaran ditutup - {{ $regCount }} peserta terdaftar</div>
                                            @elseif($comp->status === 'finished')
                                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl p-3 text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-3.5"><svg class="w-4 h-4 inline-block translate-y-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Event selesai - {{ $regCount }} peserta terdaftar</div>
                                            @endif
                                        @endif
                                        @if(in_array($comp->status, ['ongoing','finished']) && $comp->has_bracket)
                                        <a href="{{ route('public.bracket.show', $comp) }}" class="inline-flex items-center justify-center gap-2 w-full mt-3.5 py-3 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs transition-all cursor-pointer">
                                            <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Lihat Bagan Pertandingan
                                            @if($comp->status === 'ongoing')
                                                <span class="text-[10px] bg-black/20 px-2 py-0.5 rounded-full"><span class="live-dot"></span>Live</span>
                                            @endif
                                        </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
function toggleCard(id) {
    const body = document.getElementById('body-' + id);
    if (body) body.classList.toggle('hidden');
}
function getToken(id) {
    const k = 'gt_tok_' + id;
    let t = localStorage.getItem(k);
    if (!t) { t = (typeof crypto !== 'undefined' && crypto.randomUUID) ? crypto.randomUUID() : Math.random().toString(36).slice(2) + Date.now().toString(36); localStorage.setItem(k, t); }
    return t;
}
async function register(id) {
    const input = document.getElementById('grow-' + id);
    const errDiv = document.getElementById('reg-err-' + id);
    const growId = input.value.trim();
    if (!growId) { showErr(id, 'Masukkan GrowID kamu.'); return; }
    errDiv.classList.add('hidden');
    const resp = await fetch(`/tournament/${id}/register`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ grow_id: growId, client_token: getToken(id) }),
    });
    const data = await resp.json();
    if (resp.ok) {
        if (data.client_token) localStorage.setItem('gt_tok_' + id, data.client_token);
        document.getElementById('reg-wrap-' + id).innerHTML = `<div class="bg-brand-50 dark:bg-brand-900/20 border border-brand-200 dark:border-brand-800/50 text-brand-600 dark:text-brand-400 p-3 rounded-xl text-xs font-bold mt-2.5"><svg class="w-4 h-4 inline-block translate-y-[-1px] text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Berhasil terdaftar sebagai <strong>${data.grow_id}</strong>!</div>`;
    } else {
        if (data.client_token) localStorage.setItem('gt_tok_' + id, data.client_token);
        showErr(id, data.error || 'Gagal mendaftar.');
    }
}
function showErr(id, msg) {
    const e = document.getElementById('reg-err-' + id);
    if (e) { e.textContent = '<img src="{{ asset('images/items/exclamation.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> ' + msg; e.classList.remove('hidden'); }
}
document.addEventListener('DOMContentLoaded', () => {
    @foreach($competitions as $comp)
    @if($comp->has_registration && $comp->status === 'registration_open')
    (function(){ const tok = localStorage.getItem('gt_tok_{{ $comp->id }}'); if (tok) { const w = document.getElementById('reg-wrap-{{ $comp->id }}'); if (w) w.innerHTML = '<div class="bg-brand-50 dark:bg-brand-900/20 border border-brand-200 dark:border-brand-800/50 text-brand-600 dark:text-brand-400 p-3 rounded-xl text-xs font-bold mt-2.5"><svg class="w-4 h-4 inline-block translate-y-[-1px] text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Kamu sudah terdaftar di event ini.</div>'; } })();
    @endif
    @endforeach
});
</script>
@endpush

