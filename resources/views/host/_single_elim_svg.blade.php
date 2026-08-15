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

    $seId = 'se-' . substr(md5(uniqid()), 0, 8);
@endphp

<style>
    /* Bypassing browser cache: Force hide converge lines on final columns */
    .bracket-wrap .bracket-grid .round-col.final-col .match-wrapper::after,
    .bracket-wrap .bracket-grid .round-col.final-col .match-wrapper::before,
    div.bracket-grid > div.round-col.final-col > div.round-matches > div.match-wrapper::before {
        display: none !important;
        opacity: 0 !important;
        border: none !important;
        background: transparent !important;
    }

    /* Disable all original CSS lines because we use JS SVG */
    #{{ $seId }}-outer .match-wrapper::before,
    #{{ $seId }}-outer .match-wrapper::after {
        display: none !important;
        border: none !important;
    }
</style>

<div class="bracket-wrap se-bracket-outer" id="{{ $seId }}-outer" style="position:relative; padding-top:10px; overflow-x: auto;">
    <svg class="se-svg-overlay" id="{{ $seId }}-svg" aria-hidden="true" style="position: absolute; top:0; left:0; pointer-events:none; z-index:0;"></svg>
    <div class="bracket-grid" id="{{ $seId }}-grid" style="display: flex; justify-content: space-between; width: 100%; min-width: max-content; position: relative; z-index: 1;">
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
                        <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}"
                             data-seid="{{ $seId }}"
                             data-side="left"
                             data-rnd="{{ $rnd }}"
                             data-sidx="{{ $loop->index }}">
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
                            <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}"
                                 data-seid="{{ $seId }}"
                                 data-side="left"
                                 data-rnd="{{ $rnd }}"
                                 data-sidx="{{ $loop->index }}">
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
                    <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}" style="flex:none;"
                         data-seid="{{ $seId }}"
                         data-side="center"
                         data-rnd="{{ $maxRounds }}"
                         data-sidx="0">
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
                            <div class="match-wrapper {{ $isVisualBye ? 'is-bye-wrapper' : '' }}"
                                 data-seid="{{ $seId }}"
                                 data-side="right"
                                 data-rnd="{{ $rnd }}"
                                 data-sidx="{{ $loop->index }}">
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

<script>
(function() {
    var SEID = '{{ $seId }}';
    var MAX_RND = {{ $maxRounds }};
    var IS_SPLIT = {{ $isSplit ? 'true' : 'false' }};

    function wireColor() {
        if (document.documentElement.classList.contains('dark')) {
            return getComputedStyle(document.documentElement).getPropertyValue('--br-wire').trim() || '#3e4559';
        }
        return getComputedStyle(document.documentElement).getPropertyValue('--br-wire').trim() || '#d0d7e5';
    }

    function makePath(svg, d, color) {
        var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('d', d);
        p.setAttribute('stroke', color);
        p.setAttribute('stroke-width', '2');
        p.setAttribute('fill', 'none');
        p.setAttribute('stroke-linejoin', 'round');
        svg.appendChild(p);
    }

    function card(el) { return el.querySelector('.match-card') || el; }
    function cRight(el, sr) { var r = card(el).getBoundingClientRect(); return r.right - sr.left; }
    function cLeft(el, sr)  { var r = card(el).getBoundingClientRect(); return r.left - sr.left; }
    function cMidY(el, sr)  { var r = card(el).getBoundingClientRect(); return r.top + r.height/2 - sr.top; }

    function drawRoundedPath(svg, points, color, radius) {
        if (!radius) radius = 5;
        var d = 'M ' + points[0].x + ' ' + points[0].y;
        for (var i = 1; i < points.length - 1; i++) {
            var p0 = points[i-1], p1 = points[i], p2 = points[i+1];
            
            var dx1 = p0.x - p1.x, dy1 = p0.y - p1.y;
            var len1 = Math.sqrt(dx1*dx1 + dy1*dy1);
            if (len1 === 0) continue;
            var r1 = Math.min(radius, len1/2);
            var nx1 = p1.x + (dx1/len1)*r1, ny1 = p1.y + (dy1/len1)*r1;
            
            var dx2 = p2.x - p1.x, dy2 = p2.y - p1.y;
            var len2 = Math.sqrt(dx2*dx2 + dy2*dy2);
            if (len2 === 0) continue;
            var r2 = Math.min(radius, len2/2);
            var nx2 = p1.x + (dx2/len2)*r2, ny2 = p1.y + (dy2/len2)*r2;
            
            d += ' L ' + nx1 + ' ' + ny1;
            d += ' Q ' + p1.x + ' ' + p1.y + ' ' + nx2 + ' ' + ny2;
        }
        var last = points[points.length-1];
        d += ' L ' + last.x + ' ' + last.y;
        makePath(svg, d, color);
    }

    /* Asymmetrical JS SVG drawMerge - Zero Jogs, Sharp T-Junction, Rounded Corners */
    function drawMerge(svg, x1a, y1a, x1b, y1b, x2, y2, color) {
        var x1 = (x1a < x2) ? Math.max(x1a, x1b) : Math.min(x1a, x1b);
        var mx = (x1 + x2) / 2;
        
        drawRoundedPath(svg, [
            {x: x1a, y: y1a},
            {x: mx, y: y1a},
            {x: mx, y: y2}
        ], color, 6);
        
        drawRoundedPath(svg, [
            {x: x1b, y: y1b},
            {x: mx, y: y1b},
            {x: mx, y: y2}
        ], color, 6);
        
        makePath(svg, 'M'+mx+' '+y2+' L'+x2+' '+y2, color);
    }

    function drawWire(svg, x1, y1, x2, y2, color) {
        var mx = (x1 + x2) / 2;
        drawRoundedPath(svg, [
            {x: x1, y: y1},
            {x: mx, y: y1},
            {x: mx, y: y2},
            {x: x2, y: y2}
        ], color, 6);
    }

    function isBye(el) { return el.classList.contains('is-bye-wrapper'); }

    function connectLTR(svg, sr, cur, next, color) {
        if (cur.length === next.length) {
            cur.forEach(function(el, i) {
                var nx = next[i]; if (!nx) return;
                if (!isBye(el) && !isBye(nx)) {
                    drawWire(svg, cRight(el,sr), cMidY(el,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                }
            });
        } else {
            next.forEach(function(nx, ni) {
                var a = cur[ni*2], b = cur[ni*2+1];
                if (!nx || isBye(nx)) return;
                var aOk = a && !isBye(a);
                var bOk = b && !isBye(b);
                if (aOk && bOk) {
                    drawMerge(svg, cRight(a,sr), cMidY(a,sr), cRight(b,sr), cMidY(b,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                } else if (aOk) {
                    drawWire(svg, cRight(a,sr), cMidY(a,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                } else if (bOk) {
                    drawWire(svg, cRight(b,sr), cMidY(b,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                }
            });
        }
    }

    function connectRTL(svg, sr, cur, next, color) {
        if (cur.length === next.length) {
            cur.forEach(function(el, i) {
                var nx = next[i]; if (!nx) return;
                if (!isBye(el) && !isBye(nx)) {
                    drawWire(svg, cLeft(el,sr), cMidY(el,sr), cRight(nx,sr), cMidY(nx,sr), color);
                }
            });
        } else {
            next.forEach(function(nx, ni) {
                var a = cur[ni*2], b = cur[ni*2+1];
                if (!nx || isBye(nx)) return;
                var aOk = a && !isBye(a);
                var bOk = b && !isBye(b);
                if (aOk && bOk) {
                    drawMerge(svg, cLeft(a,sr), cMidY(a,sr), cLeft(b,sr), cMidY(b,sr), cRight(nx,sr), cMidY(nx,sr), color);
                } else if (aOk) {
                    drawWire(svg, cLeft(a,sr), cMidY(a,sr), cRight(nx,sr), cMidY(nx,sr), color);
                } else if (bOk) {
                    drawWire(svg, cLeft(b,sr), cMidY(b,sr), cRight(nx,sr), cMidY(nx,sr), color);
                }
            });
        }
    }

    function draw() {
        var outer = document.getElementById(SEID + '-outer');
        var svg   = document.getElementById(SEID + '-svg');
        if (!outer || !svg) return;

        var fullW = outer.scrollWidth;
        var fullH = outer.scrollHeight;
        svg.setAttribute('width',  fullW);
        svg.setAttribute('height', fullH);
        svg.style.width  = fullW + 'px';
        svg.style.height = fullH + 'px';
        while (svg.firstChild) svg.removeChild(svg.firstChild);

        var sr    = svg.getBoundingClientRect();
        var color = wireColor();

        function getWrappers(side, rnd) {
            var selector = '.match-wrapper[data-seid="'+SEID+'"][data-side="'+side+'"]';
            if (rnd) selector += '[data-rnd="'+rnd+'"]';
            return Array.from(outer.querySelectorAll(selector));
        }

        if (!IS_SPLIT) {
            for (var r = 1; r < MAX_RND; r++) {
                var cur  = getWrappers('left', r);
                var next = getWrappers('left', r + 1);
                if (cur.length && next.length) {
                    connectLTR(svg, sr, cur, next, color);
                }
            }
        } else {
            var center = getWrappers('center', MAX_RND)[0];
            
            for (var r = 1; r < MAX_RND; r++) {
                var curL  = getWrappers('left', r);
                var nextL = getWrappers('left', r + 1);
                if (curL.length && nextL.length) {
                    connectLTR(svg, sr, curL, nextL, color);
                }
            }
            if (center) {
                var lastL = getWrappers('left', MAX_RND - 1);
                if (lastL.length) connectLTR(svg, sr, lastL, [center], color);
            }

            for (var r = 1; r < MAX_RND; r++) {
                var curR  = getWrappers('right', r);
                var nextR = getWrappers('right', r + 1);
                if (curR.length && nextR.length) {
                    connectRTL(svg, sr, curR, nextR, color);
                }
            }
            if (center) {
                var lastR = getWrappers('right', MAX_RND - 1);
                if (lastR.length) connectRTL(svg, sr, lastR, [center], color);
            }
        }
    }

    function init() {
        draw();
        window.addEventListener('resize', draw);
        var mo = new MutationObserver(draw);
        mo.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        var outer = document.getElementById(SEID + '-outer');
        if (outer) outer.addEventListener('scroll', draw);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        requestAnimationFrame(function () { requestAnimationFrame(init); });
    }
})();
</script>
