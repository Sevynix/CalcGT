<?php

// 1. Determine max rounds
$upperRounds = $data['upper'] ?? [];
$lowerRounds = $data['lower'] ?? [];
$gfNodes = $data['grand_final'] ?? [];

$maxUpper = empty($upperRounds) ? 0 : max(array_keys($upperRounds));
$maxLower = empty($lowerRounds) ? 0 : max(array_keys($lowerRounds));
$maxRounds = max($maxUpper, $maxLower);

$gfCount = count($gfNodes);
$totalCols = $maxRounds + $gfCount;

?>

<div class="bracket-wrap" style="padding-top:10px; overflow-x: auto;">
    <div class="bracket-grid" style="display: flex; justify-content: space-between; width: 100%; min-width: max-content;">
        
        @for($i = 1; $i <= $maxRounds; $i++)
            <div class="round-col">
                {{-- UPPER BRACKET SECTION --}}
                @if(isset($upperRounds[$i]))
                    @php
                        $nodes = $upperRounds[$i];
                        $roundName = match($i) {
                            1 => 'Upper Babak 1',
                            2 => $maxUpper > 2 ? 'Upper Semifinal' : 'Final Upper',
                            3 => 'Final Upper',
                            default => "Upper Babak {$i}",
                        };
                        $nextRoundCount = isset($upperRounds[$i + 1]) ? count($upperRounds[$i + 1]) : 0;
                        $isStraight = $nextRoundCount > 0 && $nextRoundCount == count($nodes);
                    @endphp
                    <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(108, 99, 255, 0.3), transparent);">{{ $roundName }}</div>
                    <div class="round-matches" style="flex: 1;">
                        @foreach($nodes as $node)
                            @php
                                $isFinal = $i == $maxUpper && !empty($gfNodes);
                            @endphp
                            <div class="match-wrapper {{ $isStraight ? 'match-straight' : '' }} {{ $isFinal ? 'match-converge-down' : '' }}">
                                @include('host._bracket_match', ['match' => $node, 'bracketType' => $bracketType ?? null, 'isSeeding' => $isSeeding ?? false])
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty placeholder to maintain grid alignment if upper finishes earlier --}}
                    <div class="round-header" style="visibility:hidden;">-</div>
                    <div class="round-matches" style="flex: 1; min-height: 100px;"></div>
                @endif
                
                {{-- SPACER BETWEEN UPPER AND LOWER --}}
                <div style="height: 60px;"></div>

                {{-- LOWER BRACKET SECTION --}}
                @if(isset($lowerRounds[$i]))
                    @php
                        $nodes = $lowerRounds[$i];
                        $roundName = match($i) {
                            $maxLower => 'Final Lower',
                            $maxLower - 1 => $maxLower > 2 ? 'Semifinal Lower' : 'Lower Babak ' . $i,
                            default => "Lower Babak {$i}",
                        };
                        $nextRoundCount = isset($lowerRounds[$i + 1]) ? count($lowerRounds[$i + 1]) : 0;
                        $isStraight = $nextRoundCount > 0 && $nextRoundCount == count($nodes);
                    @endphp
                    <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(255, 165, 2, 0.3), transparent);">{{ $roundName }}</div>
                    <div class="round-matches" style="flex: 1;">
                        @foreach($nodes as $node)
                            @php
                                $isFinal = $i == $maxLower && !empty($gfNodes);
                            @endphp
                            <div class="match-wrapper {{ $isStraight ? 'match-straight' : '' }} {{ $isFinal ? 'match-converge-up' : '' }}">
                                @include('host._bracket_match', ['match' => $node, 'bracketType' => $bracketType ?? null, 'isSeeding' => $isSeeding ?? false])
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty placeholder to maintain grid alignment if lower starts later (not typical but safe) --}}
                    <div class="round-header" style="visibility:hidden;">-</div>
                    <div class="round-matches" style="flex: 1; min-height: 100px;"></div>
                @endif
            </div>
        @endfor

        {{-- GRAND FINAL SECTION (appended as extra columns) --}}
        @foreach($gfNodes as $index => $node)
            @php 
                $isIfNec = (bool)($node['is_if_necessary'] ?? false); 
                if ($isIfNec) {
                    continue;
                }
            @endphp
            <div class="round-col">
                <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(46, 213, 115, 0.3), transparent); visibility: hidden;">-</div>
                <div class="round-matches" style="flex: 1; display:flex; flex-direction:column; justify-content:center;">
                    
                    <div style="text-align:center; margin-bottom:12px;">
                        <span class="status-badge s-finished" style="background:rgba(46,213,115,.2); color:var(--success);">
                            <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> {{ $isIfNec ? 'If Necessary' : 'Grand Final' }}
                        </span>
                    </div>
                    <div class="match-wrapper" style="flex: none;">
                        @include('host._bracket_match', ['match' => $node, 'bracketType' => $bracketType ?? null, 'isSeeding' => $isSeeding ?? false])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
