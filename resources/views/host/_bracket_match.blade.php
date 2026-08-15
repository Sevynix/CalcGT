@php
    $a       = $match['participant_a'] ?? null;
    $b       = $match['participant_b'] ?? null;
    $winner  = $match['winner'] ?? null;
    $isBye   = $match['is_bye'] ?? false;
    $nodeId  = $match['id'];
    $hasWinner = !is_null($winner);

    // Calculate match status
    $statusText = '';
    $statusClass = '';
    $isReady = false;

    if ($hasWinner) {
        $statusText = 'Selesai';
        $statusClass = 'status-completed';
    } elseif ($a && $b && !$isBye) {
        $statusText = '<img src="' . asset('images/items/trophy.png') . '" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Siap';
        $statusClass = 'status-ready';
        $isReady = true;
    } else {
        $statusText = '<img src="' . asset('images/items/timer.png') . '" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Menunggu';
        $statusClass = 'status-waiting';
    }
    
    // Seeding Logic
    $isSeedingActive = !empty($isSeeding) && empty($isPublic);
    $canSeedA = false;
    $canSeedB = false;
    
    if ($isSeedingActive) {
        if ($match['round'] == 1) {
            $canSeedA = true;
            $canSeedB = true;
            if (isset($bracketType) && in_array($bracketType, ['upper_lower_4', 'upper_lower_6']) && $match['bracket_side'] === 'lower') {
                $canSeedA = false;
                $canSeedB = false;
            }
        }
        
        if (isset($bracketType) && $bracketType === 'upper_lower_6' && $match['bracket_side'] === 'upper' && $match['round'] == 2) {
            $canSeedA = true;
        }
    }
@endphp
<div class="match-card {{ $isReady ? 'ready-glow' : '' }} {{ isset($isVisualBye) && $isVisualBye ? 'is-bye-card' : '' }}">
    @if(!isset($isVisualBye) || !$isVisualBye)
        @if(!isset($isSeeding) || !$isSeeding)
            <div class="match-status {{ $statusClass }}">
                {!! $statusText !!}
                @if($hasWinner && empty($isPublic))
                    <button type="button" onclick="event.stopPropagation(); openResetWinner({{ $nodeId }})" class="reset-winner-btn text-red-500 hover:text-red-700 font-bold ml-2" title="Batalkan Hasil Pertandingan">
                        <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    </button>
                @endif
            </div>
        @endif
    @endif
    <div class="participants-container">
        {{-- Participant A --}}
        @if($a)
            <div class="participant
                {{ $hasWinner && $winner === $a ? 'winner' : '' }}
                {{ $hasWinner && $winner !== $a ? 'loser' : '' }}
                {{ $isBye ? 'bye' : '' }}"
                @if(empty($isPublic))
                    @if($canSeedA)
                        onclick="openAssign({{ $nodeId }}, 'participant_a')"
                    @elseif(!$isBye)
                        @if(!$hasWinner && $isReady)
                            onclick="openSetWinner({{ $nodeId }}, '{{ addslashes($a) }}')"
                        @elseif($hasWinner)
                            onclick="openCorrectWinner({{ $nodeId }}, '{{ addslashes($a) }}')"
                        @endif
                    @endif
                @endif>
                <span title="{{ $a }}">{{ \Illuminate\Support\Str::limit($a, 18) }}</span>
                @if($hasWinner && $winner === $a)<span class="win-icon"><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>@endif
            </div>
        @else
            @if(!isset($isVisualBye) || !$isVisualBye)
                <div class="participant empty"
                    @if($canSeedA)
                        style="cursor:pointer; color:var(--primary);"
                        onclick="openAssign({{ $nodeId }}, 'participant_a')"
                    @endif>
                    TBD
                </div>
            @endif
        @endif

        {{-- Participant B --}}
        @if($b)
            <div class="participant
                {{ $hasWinner && $winner === $b ? 'winner' : '' }}
                {{ $hasWinner && $winner !== $b ? 'loser' : '' }}
                {{ $isBye ? 'bye' : '' }}"
                @if(empty($isPublic))
                    @if($canSeedB)
                        onclick="openAssign({{ $nodeId }}, 'participant_b')"
                    @elseif(!$isBye)
                        @if(!$hasWinner && $isReady)
                            onclick="openSetWinner({{ $nodeId }}, '{{ addslashes($b) }}')"
                        @elseif($hasWinner)
                            onclick="openCorrectWinner({{ $nodeId }}, '{{ addslashes($b) }}')"
                        @endif
                    @endif
                @endif>
                <span title="{{ $b }}">{{ \Illuminate\Support\Str::limit($b, 18) }}</span>
                @if($hasWinner && $winner === $b)<span class="win-icon"><img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""></span>@endif
            </div>
        @else
            @if(!isset($isVisualBye) || !$isVisualBye)
                <div class="participant empty"
                    @if($canSeedB)
                        style="cursor:pointer; color:var(--primary);"
                        onclick="openAssign({{ $nodeId }}, 'participant_b')"
                    @endif>
                    TBD
                </div>
            @endif
        @endif
    </div>
</div>
