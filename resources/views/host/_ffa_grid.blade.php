<style>
/* Style adjustments for FFA disguised as bracket */
.ffa-as-bracket {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    padding: 10px 0;
}
.ffa-match {
    width: 220px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
}
.ffa-match:hover {
    border-color: rgba(255,255,255,0.2);
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    transform: translateY(-2px);
}
.ffa-match.passed {
    border-color: var(--success);
}
.ffa-match-slot {
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ffa-match.passed .ffa-match-slot {
    background: rgba(46, 213, 115, 0.08);
}
.ffa-name {
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ffa-status {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    background: rgba(255,255,255,0.1);
    color: var(--muted);
}
.ffa-match.passed .ffa-status {
    background: var(--success);
    color: #000;
}
</style>

<div class="ffa-container">
    @if(isset($isPublic) && $isPublic)
        <div style="margin-bottom:16px;">
            <p style="color:var(--muted);font-size:13px;">Daftar peserta Free For All.</p>
        </div>
    @else
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <p style="color:var(--muted);font-size:13px;">Host memilih siapa yang lolos dari Free For All ini.</p>
            <a href="{{ route('host.ffa.show', $competition ?? $data['competition']) }}" class="btn btn-primary btn-sm"><img src="{{ asset('images/items/roulette.png') }}" class="w-4 h-4 inline-block object-contain translate-y-[-2px]" alt=""> Kelola FFA</a>
        </div>
    @endif

    <div class="ffa-as-bracket">
        @foreach($data['participants'] as $p)
            <div class="ffa-match {{ $p['passed'] ? 'passed' : '' }}">
                <div class="ffa-match-slot">
                    <span class="ffa-name">{{ $p['name'] }}</span>
                    @if($p['passed']) 
                        <span class="ffa-status">LOLOS</span>
                    @else
                        <span class="ffa-status">FFA</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
