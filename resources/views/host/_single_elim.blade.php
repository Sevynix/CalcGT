@php
    $bracket = $data ?? $bracket ?? [];
    $winner1 = null;
    $rounds = $bracket['rounds'] ?? [];
    
    // Find actual max round ignoring empty arrays
    $maxRounds = 0;
    foreach($rounds as $rnd => $matches) {
        if (count($matches) > 0) {
            $maxRounds = max($maxRounds, $rnd);
        }
    }
    
    // Check if we should split (only if R1 has >= 4 matches, meaning >= 8 participants)
    $firstKey = !empty($rounds) ? array_key_first($rounds) : null;
    $r1Count = $firstKey !== null ? count($rounds[$firstKey]) : 0;
    $isSplit = $r1Count >= 4;

    // Find overall winner if finished
    foreach($rounds as $rnd => $matches) {
        if ($rnd == $maxRounds && count($matches) == 1 && ($matches[0]['winner'] ?? null)) {
            $winner1 = $matches[0]['winner'];
        }
    }
@endphp

<style>
    /* Bypassing browser cache: Force hide converge lines on final columns */
    .bracket-wrap .bracket-grid .round-col.final-col .match-wrapper::after,
    .bracket-wrap .bracket-grid .round-col.final-col .match-wrapper::before,
    div.bracket-grid > div.round-col.final-col > div.round-matches > div.match-wrapper::after,
    div.bracket-grid > div.round-col.final-col > div.round-matches > div.match-wrapper::before {
        display: none !important;
        opacity: 0 !important;
        border: none !important;
        background: transparent !important;
    }
</style>

<div class="bracket-wrap" style="padding-top:10px; overflow-x: auto;">
    <div class="bracket-grid" style="display: flex; justify-content: space-between; width: 100%; min-width: max-content;">
        @if(!$isSplit)
            {{-- ── NORMAL (LEFT-TO-RIGHT) ── --}}
            @foreach($rounds as $rnd => $matches)
            @if(count($matches) == 0) @continue @endif
            <div class="round-col {{ $rnd == $maxRounds ? 'final-col' : '' }}">
                <div class="round-header">
                    @php
                        $targetRound = $bracket['target_round'] ?? null;
                        if ($targetRound && $rnd == $targetRound) { echo 'AKHIR FASE'; }
                        elseif ($rnd == $maxRounds) { echo 'FINAL'; }
                        elseif ($rnd == $maxRounds - 1) { echo 'SEMIFINAL'; }
                        else { echo "RONDE {$rnd}"; }
                    @endphp
                </div>
                <div class="round-matches">
                    @foreach($matches as $match)
                        @php
                            $isSeedingMode = ($bracket['status'] ?? '') === 'seeding';
                            $isVisualBye = !empty($match['is_bye']) && !$isSeedingMode;
                        @endphp
                        <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}">
                            @include('host._bracket_match', [
                                'match' => $match, 'competition' => $competition,
                                'isVisualBye' => $isVisualBye, 'isSeedingMode' => $isSeedingMode,
                                'isPublic' => $isPublic ?? false,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        @else
            {{-- ── SPLIT / MIRRORED (LEFT & RIGHT) ── --}}
            
            {{-- 1. LEFT SIDE --}}
            @for($rnd = 1; $rnd <= $maxRounds; $rnd++)
                @php
                    $matches = $rounds[$rnd] ?? [];
                    $halfCount = count($matches) / 2;
                    // If it's the very last round and there's only 1 match, we skip it here (it goes in center)
                    if ($rnd == $maxRounds && count($matches) == 1) continue;
                    
                    $leftMatches = array_slice($matches, 0, floor($halfCount));
                @endphp
                @if(count($leftMatches) > 0)
                <div class="round-col {{ $rnd == $maxRounds ? 'final-col' : '' }}">
                    <div class="round-header">
                        @php
                            $targetRound = $bracket['target_round'] ?? null;
                            if ($targetRound && $rnd == $targetRound) { echo 'AKHIR FASE'; }
                            elseif ($rnd == $maxRounds - 1) { echo 'SEMIFINAL'; }
                            else { echo "RONDE {$rnd}"; }
                        @endphp
                    </div>
                    <div class="round-matches">
                        @foreach($leftMatches as $match)
                            @php
                                $isSeedingMode = ($bracket['status'] ?? '') === 'seeding';
                                $isVisualBye = !empty($match['is_bye']) && !$isSeedingMode;
                            @endphp
                            <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}">
                                @include('host._bracket_match', [
                                    'match' => $match, 'competition' => $competition,
                                    'isVisualBye' => $isVisualBye, 'isSeedingMode' => $isSeedingMode,
                                    'isPublic' => $isPublic ?? false,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endfor

            {{-- 2. CENTER (FINAL MATCH) OR SPACER --}}
            @if(count($rounds[$maxRounds] ?? []) == 1)
            <div class="round-col center-col">
                <div class="round-header">FINAL</div>
                <div class="round-matches" style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                    @php 
                        $match = $rounds[$maxRounds][0]; 
                        $isSeedingMode = ($bracket['status'] ?? '') === 'seeding';
                        $isVisualBye = !empty($match['is_bye']) && !$isSeedingMode;
                    @endphp
                    <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}" style="flex:none;">
                        @include('host._bracket_match', [
                            'match' => $match, 'competition' => $competition,
                            'isVisualBye' => $isVisualBye, 'isSeedingMode' => $isSeedingMode,
                            'isPublic' => $isPublic ?? false,
                        ])
                    </div>
                </div>
            </div>
            @else
            {{-- Spacer for when there is no final match in the center --}}
            <div style="width: 60px;"></div>
            @endif

            {{-- 3. RIGHT SIDE (REVERSED) --}}
            @for($rnd = $maxRounds; $rnd >= 1; $rnd--)
                @php
                    $matches = $rounds[$rnd] ?? [];
                    $halfCount = count($matches) / 2;
                    if ($rnd == $maxRounds && count($matches) == 1) continue;
                    
                    $rightMatches = array_slice($matches, ceil($halfCount));
                @endphp
                @if(count($rightMatches) > 0)
                <div class="round-col reverse-flow {{ $rnd == $maxRounds ? 'final-col' : '' }}">
                    <div class="round-header">
                        @php
                            $targetRound = $bracket['target_round'] ?? null;
                            if ($targetRound && $rnd == $targetRound) { echo 'AKHIR FASE'; }
                            elseif ($rnd == $maxRounds - 1) { echo 'SEMIFINAL'; }
                            else { echo "RONDE {$rnd}"; }
                        @endphp
                    </div>
                    <div class="round-matches">
                        @foreach($rightMatches as $match)
                            @php
                                $isSeedingMode = ($bracket['status'] ?? '') === 'seeding';
                                $isVisualBye = !empty($match['is_bye']) && !$isSeedingMode;
                            @endphp
                            <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}">
                                @include('host._bracket_match', [
                                    'match' => $match, 'competition' => $competition,
                                    'isVisualBye' => $isVisualBye, 'isSeedingMode' => $isSeedingMode,
                                    'isPublic' => $isPublic ?? false,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endfor

        @endif
    </div>
</div>
