@php
    $bracketId = $bracketId ?? 'bracket-' . uniqid();
    $upperRounds = $data['upper'] ?? [];
    $lowerRounds = $data['lower'] ?? [];
    $gfNodes = $data['grand_final'] ?? [];
    $maxUpper = empty($upperRounds) ? 0 : max(array_keys($upperRounds));
    $maxLower = empty($lowerRounds) ? 0 : max(array_keys($lowerRounds));
    $maxRounds = max($maxUpper, $maxLower);
    $hasCenter = !empty($gfNodes);
    $cols = $maxRounds + ($hasCenter ? 1 : 0);
@endphp

<style>
    #{{ $bracketId }}-outer .match-wrapper::before,
    #{{ $bracketId }}-outer .match-wrapper::after {
        display: none !important;
    }
</style>

<div class="ul-bracket-outer" id="{{ $bracketId }}-outer">
    <div class="bracket-wrap ul-bracket-wrap-inner" style="position: relative; overflow-x: auto; overflow-y: hidden; padding-top: 10px; padding-bottom: 20px;">
        
        <svg class="ul-svg-overlay" id="{{ $bracketId }}-svg" aria-hidden="true" style="position: absolute; top: 0; left: 0; pointer-events: none; z-index: 0;"></svg>

        <div id="{{ $bracketId }}-wrap" class="bracket-grid" style="display: grid; grid-template-columns: repeat({{ $cols }}, minmax(250px, 1fr)); grid-template-rows: auto 60px auto; column-gap: 30px; width: 100%; min-width: max-content; padding: 0 15px;">
            
            {{-- UPPER BRACKET --}}
            @for($i = 1; $i <= $maxRounds; $i++)
                <div class="round-col" style="grid-column: {{ $i }}; grid-row: 1; display: flex; flex-direction: column; justify-content: flex-start; z-index: 1; height: 100%;">
                    @if(isset($upperRounds[$i]))
                        @php
                            $nodes = $upperRounds[$i];
                            $roundName = match($i) {
                                1 => 'Upper Babak 1',
                                2 => $maxUpper > 2 ? 'Upper Semifinal' : 'Final Upper',
                                3 => 'Final Upper',
                                default => "Upper Babak {$i}",
                            };
                        @endphp
                        <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(108, 99, 255, 0.3), transparent);">{{ $roundName }}</div>
                        <div class="round-matches" style="flex: 1; display: flex; flex-direction: column; justify-content: space-around;">
                            @foreach($nodes as $nodeIdx => $node)
                                @php
                                    $isVisualBye = !empty($node['is_bye']) && !($isSeeding ?? false);
                                    $isHideConnector = $isVisualBye;
                                    $wrapperClasses = ['match-wrapper'];
                                    if ($isVisualBye) $wrapperClasses[] = 'is-bye-wrapper';
                                    if ($isHideConnector) $wrapperClasses[] = 'hide-left-connector';
                                @endphp
                                <div class="{{ implode(' ', $wrapperClasses) }}" 
                                     data-bid="{{ $bracketId }}" data-sec="upper" data-rnd="{{ $i }}" data-idx="{{ $nodeIdx }}">
                                    @if($isVisualBye)
                                        <div style="height: 64px; margin: 5px 0;"></div>
                                    @else
                                        @include('host._bracket_match', ['match' => $node, 'bracketId' => $bracketId])
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endfor

            {{-- LOWER BRACKET --}}
            @for($i = 1; $i <= $maxRounds; $i++)
                <div class="round-col" style="grid-column: {{ $i }}; grid-row: 3; display: flex; flex-direction: column; justify-content: flex-start; z-index: 1; height: 100%;">
                    @if(isset($lowerRounds[$i]))
                        @php
                            $nodes = $lowerRounds[$i];
                            $roundName = match($i) {
                                $maxLower => 'Final Lower',
                                $maxLower - 1 => 'Lower Semifinal',
                                default => "Lower Babak {$i}",
                            };
                        @endphp
                        <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(255, 165, 2, 0.3), transparent);">{{ $roundName }}</div>
                        <div class="round-matches" style="flex: 1; display: flex; flex-direction: column; justify-content: space-around;">
                            @foreach($nodes as $nodeIdx => $node)
                                @php
                                    $isVisualBye = !empty($node['is_bye']) && !($isSeeding ?? false);
                                    $isHideConnector = $isVisualBye;
                                    $wrapperClasses = ['match-wrapper'];
                                    if ($isVisualBye) $wrapperClasses[] = 'is-bye-wrapper';
                                    if ($isHideConnector) $wrapperClasses[] = 'hide-left-connector';
                                @endphp
                                <div class="{{ implode(' ', $wrapperClasses) }}" 
                                     data-bid="{{ $bracketId }}" data-sec="lower" data-rnd="{{ $i }}" data-idx="{{ $nodeIdx }}">
                                    @if($isVisualBye)
                                        <div style="height: 64px; margin: 5px 0;"></div>
                                    @else
                                        @include('host._bracket_match', ['match' => $node, 'bracketId' => $bracketId])
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endfor

            {{-- GRAND FINAL --}}
            @if($hasCenter)
                <div class="round-col center-col" style="grid-column: {{ $cols }}; grid-row: 1 / span 3; display: flex; flex-direction: column; justify-content: center; z-index: 1;">
                    <div class="round-header" style="background: linear-gradient(90deg, transparent, rgba(46, 213, 115, 0.3), transparent); visibility: hidden;">-</div>
                    <div class="round-matches" style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                        <div style="text-align:center; margin-bottom:12px;">
                            <span style="display:inline-flex; align-items:center; gap:6px; background:rgba(46, 213, 115, 0.15); color:#2ed573; padding:6px 14px; border-radius:20px; font-size:11px; font-weight:800; text-transform:uppercase; border:1px solid rgba(46, 213, 115, 0.3);">
                                <img src="{{ asset('images/items/trophy.png') }}" class="w-4 h-4 object-contain translate-y-[-1px]" alt="">
                                Grand Final
                            </span>
                        </div>
                        @if(!empty($gfNodes))
                            <div class="match-wrapper" data-bid="{{ $bracketId }}" data-sec="grand_final" data-rnd="1" data-idx="0" style="flex: none;">
                                @include('host._bracket_match', ['match' => $gfNodes[0], 'bracketId' => $bracketId])
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
(function () {
    var BID       = '{{ $bracketId }}';
    var UMAX      = {{ $maxUpper }};
    var LMAX      = {{ $maxLower }};
    var HAS_GF    = {{ $hasCenter ? 'true' : 'false' }};

    function wireColor() {
        return getComputedStyle(document.documentElement)
                   .getPropertyValue('--br-wire').trim() || '#475569';
    }

    function card(el) { return el.querySelector('.match-card') || el; }
    function cMidY(el, sr)  { var r = card(el).getBoundingClientRect(); return r.top  + r.height/2 - sr.top; }
    function cRight(el, sr) { return card(el).getBoundingClientRect().right  - sr.left; }
    function cLeft(el, sr)  { return card(el).getBoundingClientRect().left   - sr.left; }

    function makePath(svg, d, color) {
        var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('d', d);
        p.setAttribute('stroke', color);
        p.setAttribute('stroke-width', '1.5');
        p.setAttribute('fill', 'none');
        p.setAttribute('stroke-linejoin', 'round');
        svg.appendChild(p);
    }

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

    function drawWire(svg, x1, y1, x2, y2, color) {
        var mx = (x1 + x2) / 2;
        drawRoundedPath(svg, [
            {x: x1, y: y1},
            {x: mx, y: y1},
            {x: mx, y: y2},
            {x: x2, y: y2}
        ], color, 6);
    }

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
                    drawMerge(svg, cRight(a,sr), cMidY(a,sr), cRight(b,sr), cMidY(b,sr), cLeft(nx,sr),  cMidY(nx,sr), color);
                } else if (aOk) {
                    drawWire(svg, cRight(a,sr), cMidY(a,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                } else if (bOk) {
                    drawWire(svg, cRight(b,sr), cMidY(b,sr), cLeft(nx,sr), cMidY(nx,sr), color);
                }
            });
        }
    }

    function draw() {
        var wrap  = document.getElementById(BID + '-wrap');
        var svg   = document.getElementById(BID + '-svg');
        if (!wrap || !svg) return;

        var fullW = wrap.scrollWidth;
        var fullH = wrap.scrollHeight;
        svg.setAttribute('width',  fullW);
        svg.setAttribute('height', fullH);
        svg.style.width  = fullW + 'px';
        svg.style.height = fullH + 'px';
        while (svg.firstChild) svg.removeChild(svg.firstChild);

        var sr    = svg.getBoundingClientRect();
        var color = wireColor();

        function wrappers(sec, rnd) {
            return Array.from(wrap.querySelectorAll(
                '.match-wrapper[data-bid="' + BID + '"][data-sec="' + sec + '"][data-rnd="' + rnd + '"]'
            ));
        }

        for (var ur = 1; ur < UMAX; ur++) {
            var cur  = wrappers('upper', ur);
            var next = wrappers('upper', ur + 1);
            if (cur.length && next.length) connectLTR(svg, sr, cur, next, color);
        }

        for (var lr = 1; lr < LMAX; lr++) {
            var lcur  = wrappers('lower', lr);
            var lnext = wrappers('lower', lr + 1);
            if (lcur.length && lnext.length) connectLTR(svg, sr, lcur, lnext, color);
        }

        if (HAS_GF) {
            var gfEl = wrap.querySelector('.match-wrapper[data-bid="' + BID + '"][data-sec="grand_final"]');
            if (gfEl) {
                var gfX = cLeft(gfEl, sr);
                var gfY = cMidY(gfEl, sr);
                var uEls = wrappers('upper', UMAX);
                var lEls = wrappers('lower', LMAX);
                
                var uOk = uEls.length && !isBye(uEls[0]);
                var lOk = lEls.length && !isBye(lEls[0]);
                
                if (uOk && lOk) {
                    drawMerge(svg, cRight(uEls[0], sr), cMidY(uEls[0], sr), cRight(lEls[0], sr), cMidY(lEls[0], sr), gfX, gfY, color);
                } else if (uOk) {
                    drawWire(svg, cRight(uEls[0], sr), cMidY(uEls[0], sr), gfX, gfY, color);
                } else if (lOk) {
                    drawWire(svg, cRight(lEls[0], sr), cMidY(lEls[0], sr), gfX, gfY, color);
                }
            }
        }
    }

    function init() {
        draw();
        window.addEventListener('resize', draw);
        var mo = new MutationObserver(draw);
        mo.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        var outer = document.getElementById(BID + '-outer');
        if (outer) outer.addEventListener('scroll', draw);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        requestAnimationFrame(function () { requestAnimationFrame(init); });
    }
})();
</script>
