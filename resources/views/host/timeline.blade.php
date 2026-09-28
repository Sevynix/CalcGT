@extends('layouts.host')
@section('title', 'Timeline')

@push('styles')
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
            @if($tournament->discord_url)
            <div class="mt-4 flex items-center justify-center gap-3">
                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Information and rules</span>
                <a href="{{ $tournament->discord_url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:opacity-90 hover:scale-105 active:scale-95 shadow-md"
                   style="background: linear-gradient(135deg, #5865F2 0%, #7289da 100%)">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M13.545 2.907a13.227 13.227 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.19 12.19 0 0 0-3.658 0 8.258 8.258 0 0 0-.412-.833.051.051 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.041.041 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032c.001.014.01.028.021.037a13.276 13.276 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019c.308-.42.582-.863.818-1.329a.05.05 0 0 0-.01-.059.051.051 0 0 0-.018-.011 8.875 8.875 0 0 1-1.248-.595.05.05 0 0 1-.02-.066.051.051 0 0 1 .015-.019c.084-.063.168-.129.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.052.052 0 0 1 .053.007c.08.066.164.132.248.195a.051.051 0 0 1-.004.085 8.254 8.254 0 0 1-1.249.594.05.05 0 0 0-.03.03.052.052 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.235 13.235 0 0 0 4.001-2.02.049.049 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.034.034 0 0 0-.02-.019Zm-8.198 7.307c-.789 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612Zm5.316 0c-.788 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612Z"/>
                    </svg>
                    Discord
                </a>
            </div>
            @endif
        </div>

        @if($competitions->isEmpty())
            <div class="text-center p-12 bg-white dark:bg-slate-800/80 border border-dashed border-slate-200 dark:border-slate-700/80 rounded-3xl max-w-md mx-auto">
                <img src="{{ asset('images/items/exclamation.png') }}" class="w-14 h-14 mx-auto mb-4 drop-shadow-md object-contain" alt="Exclamation">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Belum Ada Lomba</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Hubungi admin untuk menambahkan lomba ke tournament ini.</p>
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

                @foreach($competitions as $i => $c)
                @php
                    $isEven    = $i % 2 === 1;
                    $regCount  = $c->registrations->count();
                    $slotCount = $c->effectiveSlotCount();
                    $pct       = $c->has_registration ? ($slotCount > 0 ? min(100, ($regCount / $slotCount) * 100) : 0) : 100;
                    $isActive  = in_array($c->status, ['registration_open', 'ongoing']);
                    $barFillClass = match($c->status) {
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
                        <div class="tl-label">Lomba {{ $c->reveal_order }}</div>
                        <div class="tl-dot {{ $isActive ? 'pulse' : '' }}"></div>
                    </div>

                    <div class="tl-connector"></div>

                    {{-- Native GrowtopiaHelp Card --}}
                    <div class="tl-card-wrap">
                        <div class="group relative flex flex-col rounded-3xl overflow-hidden border border-slate-200/60 dark:border-slate-800/80 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-white dark:bg-slate-800 min-h-[160px] p-5 w-full" id="card-{{ $c->id }}">
                            
                            <!-- Blurry Lock Background -->
                            <div class="absolute -right-6 top-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.15] blur-[8px] group-hover:opacity-25 pointer-events-none transition-opacity duration-300 z-0">
                                <img loading="lazy" src="{{ $randomLockBg }}" alt="" class="w-full h-full object-contain">
                            </div>

                            <div class="relative z-10 flex flex-col flex-1 gap-2">
                                <div>
                                    <div class="flex items-center justify-end">
                                        <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
                                            {{ $c->status === 'hidden' ? 'HIDDEN' : str_replace('_', ' ', $c->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-1">
                                        <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 tracking-tight leading-snug">
                                            {{ $c->status === 'hidden' ? 'Belum Diungkap' : $c->name }}
                                        </h2>
                                        <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed mt-1 max-w-[90%]">
                                            @if($c->status === 'hidden')
                                                Detail lomba masih dirahasiakan oleh host. Nantikan pengumuman selanjutnya!
                                            @else
                                                {{ $c->detail ? Str::limit($c->detail, 85) : 'Kompetisi resmi tournament GrowtopiaHelp.' }}
                                            @endif
                                        </p>
                                    </div>

                                    @if($c->status !== 'hidden' && $c->photo_path)
                                    <div class="my-2 overflow-hidden rounded-2xl border border-slate-200/60 dark:border-slate-700/60 shadow-sm">
                                        <img src="{{ asset('storage/' . $c->photo_path) }}" class="w-full aspect-[16/9] object-cover" alt="{{ $c->name }}">
                                    </div>
                                    @endif

                                    @if($c->status !== 'hidden' && ($c->has_registration || $c->has_bracket))
                                    <div class="my-2">
                                        <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $barFillClass }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-[11px] font-bold text-slate-500 dark:text-slate-400 mt-1">
                                            <span>{{ $c->effectiveSlotCount() }} Slot</span>
                                            @if($c->has_registration)
                                                <span>{{ $regCount }} Terdaftar</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                
                                {{-- Admin Actions --}}
                                <div class="flex items-center justify-start flex-wrap gap-2 text-slate-800 dark:text-slate-200 font-bold text-sm pt-4 mt-auto border-t border-slate-100 dark:border-slate-700/60">
                                    @if($c->status === 'hidden')
                                        <form method="POST" action="{{ route('host.competitions.reveal', $c) }}">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-brand-600 to-sky-500 text-white font-bold text-xs shadow-sm hover:brightness-110 transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Reveal
                                            </button>
                                        </form>

                                    @elseif($c->status === 'revealed')
                                        @if($c->has_registration)
                                            <form method="POST" action="{{ route('host.competitions.open-registration', $c) }}">
                                                @csrf
                                                <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-bold text-xs border border-amber-200 dark:border-amber-700/50 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-all cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                    Buka Pendaftaran
                                                </button>
                                            </form>
                                        @else
                                            @if($c->has_bracket)
                                                <a href="{{ route('host.manual-participants.manage', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition-all">
                                                    <img src="{{ asset('images/items/pencil.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Kelola Peserta & Buat Bagan
                                                </a>
                                            @endif
                                        @endif

                                    @elseif($c->status === 'registration_open')
                                        <form method="POST" action="{{ route('host.competitions.close-registration', $c) }}">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-bold text-xs border border-amber-200 dark:border-amber-700/50 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                Tutup Pendaftaran
                                            </button>
                                        </form>
                                        @if($tournament->status === 'test_drive')
                                        <form method="POST" action="{{ route('host.competitions.seed-users', $c) }}" x-data @submit.prevent="$store.modal.show('Tambahkan 40 user dummy?', () => $el.submit(), 'Konfirmasi', 'Ya, Tambahkan', 'primary')">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-600 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                40 Dummy
                                            </button>
                                        </form>
                                        @endif

                                    @elseif($c->status === 'registration_closed')
                                        @if($c->has_registration)
                                            <a href="{{ route('host.spin.show', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                                                <img src="{{ asset('images/items/roulette.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-1px]" alt="Roulette">
                                                Spin Wheel
                                            </a>
                                            <form method="POST" action="{{ route('host.competitions.open-registration', $c) }}" x-data @submit.prevent="$store.modal.show('Buka kembali pendaftaran?', () => $el.submit(), 'Konfirmasi', 'Ya, Buka', 'primary')">
                                                @csrf
                                                <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-bold text-xs border border-amber-200 dark:border-amber-700/50 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-all cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                    Buka Pendaftaran
                                                </button>
                                            </form>
                                        @endif

                                    @elseif($c->status === 'ongoing')
                                        @if($c->latestPhase()?->isFfa())
                                            <a href="{{ route('host.ffa.show', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition-all">🎲 Kelola FFA</a>
                                        @elseif($c->has_bracket)
                                            <a href="{{ route('host.bracket', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                                                <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="Trophy">
                                                Bracket
                                            </a>
                                        @endif
                                        @if($c->has_bracket || $c->has_registration)
                                        <form method="POST" action="{{ route('host.competitions.finish', $c) }}" x-data
                                              @submit.prevent="$store.modal.show('Tandai lomba ini selesai?', () => $el.submit(), 'Konfirmasi', 'Ya, Selesai', 'primary')">
                                            @csrf
                                            <button class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-600 hover:bg-slate-200 transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Selesai
                                            </button>
                                        </form>
                                        @endif

                                    @elseif($c->status === 'finished')
                                        @if($c->has_bracket)
                                            <a href="{{ route('host.bracket', $c) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-600 hover:bg-slate-200 transition-all">
                                                <img src="{{ asset('images/items/bulletin.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt="Bulletin">
                                                Lihat Hasil
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
    function toggleCard(id) {
        const body = document.getElementById('body-' + id);
        if (body) body.classList.toggle('hidden');
    }
    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('timeline_scrollpos', window.scrollY);
    });
    document.addEventListener('DOMContentLoaded', function() {
        var scrollpos = sessionStorage.getItem('timeline_scrollpos');
        if (scrollpos) {
            window.scrollTo(0, parseInt(scrollpos));
            sessionStorage.removeItem('timeline_scrollpos');
        }
    });
</script>
@endpush
