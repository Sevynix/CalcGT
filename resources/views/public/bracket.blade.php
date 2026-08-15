@extends('layouts.app')
@section('title', 'Bagan - ' . $competition->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bracket.css') }}?v={{ filemtime(public_path('css/bracket.css')) }}">
<style>
:root {
    --bg:          #ffffff;
    --surface:     #ffffff;
    --card:        #ffffff;
    --card2:       #f8fafc;
    --border:      #e2e8f0;
    --border2:     #cbd5e1;
    --azure:       #0e59c3;
    --azure-dim:   #0b479c;
    --azure-glow:  rgba(14,89,195,.15);
    --azure-xlight:rgba(14,89,195,.06);
    --gold:        #d97706;
    --gold-dim:    #b45309;
    --text:        #0f172a;
    --text-mid:    #334155;
    --text-dim:    #475569;
    --text-muted:  #64748b;
    --r:           14px;
    --r-sm:        9px;
}

.dark, .dark * {
    --bg:          #0f172a;
    --surface:     #0f172a;
    --card:        #1e293b;
    --card2:       #1e293b;
    --border:      #334155;
    --border2:     #475569;
    --azure:       #3b82f6;
    --azure-dim:   #60a5fa;
    --azure-glow:  rgba(59,130,246,.2);
    --azure-xlight:rgba(59,130,246,.1);
    --gold:        #f59e0b;
    --gold-dim:    #d97706;
    --text:        #f8fafc;
    --text-mid:    #e2e8f0;
    --text-dim:    #cbd5e1;
    --text-muted:  #94a3b8;
}

.participant { cursor: default; }
.participant:hover:not(.bye):not(.empty):not(.winner-set) { background: var(--azure-xlight); }
.bracket-wrap { margin-top: 12px; }
.no-bracket { margin-top: 14px; }
.phase-tabs { margin-top: 14px; }
.winner-banner { margin-top: 14px; }

.bracket-page-header {
    padding: 16px 0 12px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 14px;
}
.bracket-page-kicker {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 10px;
}
.bracket-page-kicker-line { width: 20px; height: 2px; background: var(--azure); border-radius: 999px; }
.bracket-page-kicker-text { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--azure); }
.bracket-page-title {
    font-size: 26px; font-weight: 800;
    letter-spacing: -0.5px; margin-bottom: 4px;
    display: flex; align-items: center; gap: 10px;
    color: var(--text);
}
.bracket-page-title .trophy-icon { font-size: 22px; }
.bracket-page-sub { color: var(--text-dim); font-size: 14px; }
</style>
@endpush

@section('content')
<div class="container">
    <div class="bracket-page-header">
        <div class="flex justify-between items-center">
            <div>
                <div class="bracket-page-title">
                    <span class="trophy-icon"><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>
                    {{ $competition->name }}
                </div>
                <p class="bracket-page-sub">{{ $competition->tournament->name }}</p>
            </div>
            <div>
                <a href="{{ route('public.tournament.show', $competition->tournament) }}#card-{{ $competition->id }}" class="btn btn-ghost">← Kembali</a>
            </div>
        </div>
    </div>

    @php
        $finalWinner = null;
        $targetData = $bracket2 ?? $bracket1;
        if ($targetData && (($targetData['status'] ?? '') === 'finished' || ($competition->status ?? '') === 'finished')) {
            if (in_array($targetData['bracket_type'] ?? '', ['upper_lower_4', 'upper_lower_6'])) {
                $gf = $targetData['grand_final'] ?? [];
                if (!empty($gf)) {
                    if (!empty($gf[1]['winner'])) {
                        $finalWinner = $gf[1]['winner'];
                    } elseif (!empty($gf[0]['winner'])) {
                        $finalWinner = $gf[0]['winner'];
                    }
                }
            } elseif (($targetData['bracket_type'] ?? '') === 'single_elim') {
                $rnds = $targetData['rounds'] ?? [];
                if (!empty($rnds)) {
                    $maxR = max(array_keys($rnds));
                    if (!empty($rnds[$maxR][0]['winner'])) {
                        $finalWinner = $rnds[$maxR][0]['winner'];
                    }
                }
            }
        }
    @endphp

    @if($finalWinner)
    <div class="winner-banner-wrapper">
        <div class="winner-banner">
            <div class="winner-banner-bg"></div>
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

    @if(!$bracket1)
        <div class="card no-bracket" style="padding: 72px 40px; text-align: center;">
            <div style="font-size:52px;margin-bottom:18px;filter:drop-shadow(0 0 12px rgba(99,102,241,.2))">⏳</div>
            <h2 style="font-size:22px; font-weight:800; margin-bottom:10px;">Bagan Belum Tersedia</h2>
            <p class="muted">Bagan pertandingan untuk event ini belum disusun oleh penyelenggara.</p>
        </div>
    @else
        {{-- Phase 1 --}}
        <div class="phase-section">
            <div class="phase-header">
                <span class="status-badge s-{{ $bracket1['status'] ?? 'ongoing' }}">{{ ($bracket1['status'] ?? 'ongoing') === 'finished' ? 'Selesai' : (($bracket1['status'] ?? 'ongoing') === 'ongoing' ? 'Berlangsung' : 'Menunggu') }}</span>
            </div>
            <div class="phase-body" id="phase-1-wrap">
                @if(in_array($bracket1['bracket_type'], ['upper_lower_4', 'upper_lower_6']))
                    @include('host._upper_lower_bracket', ['data' => $bracket1, 'competition' => $competition, 'isPublic' => true])
                @elseif($bracket1['bracket_type'] === 'round_robin')
                    @include('host._group_stage', ['data' => $bracket1, 'isPublic' => true])
                @elseif($bracket1['bracket_type'] === 'ffa')
                    @include('host._ffa_grid', ['data' => $bracket1, 'competition' => $competition, 'isPublic' => true])
                @else
                    @php
                        $isImperfect = false;
                        $rnds = $bracket1['rounds'] ?? [];
                        
                        $firstKey = !empty($rnds) ? array_key_first($rnds) : null;
                        $r1Count = $firstKey !== null ? count($rnds[$firstKey]) : 0;
                        $isSplit = $r1Count >= 4;
                        $maxRounds = 0;
                        foreach($rnds as $rnd => $matches) {
                            if (count($matches) > 0) $maxRounds = max($maxRounds, $rnd);
                        }
                        $hasCenter = count($rnds[$maxRounds] ?? []) == 1;

                        // if ($isSplit && !$hasCenter) {
                        //     $isImperfect = true;
                        // }

                        if (!$isImperfect) {
                            foreach ($rnds as $rnd => $matches) {
                                if ($rnd > 1) {
                                    $prevCount = count($rnds[$rnd - 1] ?? []);
                                    $currCount = count($matches);
                                    if ($prevCount > 0 && $currCount > 0 && $currCount != $prevCount / 2) {
                                        $isImperfect = true;
                                        break;
                                    }
                                }
                            }
                        }
                    @endphp
                    @if($isImperfect)
                        @include('host._single_elim_svg', ['data' => $bracket1, 'competition' => $competition, 'isPublic' => true])
                    @else
                        @include('host._single_elim', ['data' => $bracket1, 'competition' => $competition, 'isPublic' => true])
                    @endif
                @endif
            </div>
        </div>

        @if($bracket2)
        <div class="phase-section" style="margin-top:40px;">
            <div class="phase-header">
                <span class="status-badge s-{{ $bracket2['status'] ?? 'pending' }}">{{ ($bracket2['status'] ?? 'pending') === 'finished' ? 'Selesai' : (($bracket2['status'] ?? 'pending') === 'ongoing' ? 'Berlangsung' : 'Menunggu') }}</span>
            </div>
            <div class="phase-body" id="phase-2-wrap">
                @if(in_array($bracket2['bracket_type'], ['upper_lower_4', 'upper_lower_6']))
                    @include('host._upper_lower_bracket', ['data' => $bracket2, 'competition' => $competition, 'isPublic' => true])
                @elseif($bracket2['bracket_type'] === 'round_robin')
                    @include('host._group_stage', ['data' => $bracket2, 'isPublic' => true])
                @elseif($bracket2['bracket_type'] === 'ffa')
                    @include('host._ffa_grid', ['data' => $bracket2, 'competition' => $competition, 'isPublic' => true])
                @else
                    @php
                        $isImperfect = false;
                        $rnds = $bracket2['rounds'] ?? [];
                        
                        $firstKey = !empty($rnds) ? array_key_first($rnds) : null;
                        $r1Count = $firstKey !== null ? count($rnds[$firstKey]) : 0;
                        $isSplit = $r1Count >= 4;
                        $maxRounds = 0;
                        foreach($rnds as $rnd => $matches) {
                            if (count($matches) > 0) $maxRounds = max($maxRounds, $rnd);
                        }
                        $hasCenter = count($rnds[$maxRounds] ?? []) == 1;

                        // if ($isSplit && !$hasCenter) {
                        //     $isImperfect = true;
                        // }

                        if (!$isImperfect) {
                            foreach ($rnds as $rnd => $matches) {
                                if ($rnd > 1) {
                                    $prevCount = count($rnds[$rnd - 1] ?? []);
                                    $currCount = count($matches);
                                    if ($prevCount > 0 && $currCount > 0 && $currCount != $prevCount / 2) {
                                        $isImperfect = true;
                                        break;
                                    }
                                }
                            }
                        }
                    @endphp
                    @if($isImperfect)
                        @include('host._single_elim_svg', ['data' => $bracket2, 'competition' => $competition, 'isPublic' => true])
                    @else
                        @include('host._single_elim', ['data' => $bracket2, 'competition' => $competition, 'isPublic' => true])
                    @endif
                @endif
            </div>
        </div>
        @endif
    @endif
</div>
@endsection





