@php $bracket = $data ?? $bracket ?? []; $allFinished = ($bracket['status'] ?? '') === 'finished'; @endphp
@if($allFinished)
<div class="winner-banner">
    <h2>✅ Fase Grup Selesai</h2>
    <p>Silakan mulai Fase berikutnya (Playoff) dari menu di atas.</p>
</div>
@endif

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
    @foreach($bracket['groups'] as $group)
    <div class="card" style="padding:16px;">
        <h3 style="margin-bottom:12px; font-size:16px;">{{ $group['name'] }}</h3>
        
        {{-- Standings Table --}}
        <table style="width:100%; text-align:left; font-size:13px; border-collapse:collapse; margin-bottom: 24px;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color:var(--muted);">
                    <th style="padding:8px 0;">Peserta</th>
                    <th style="padding:8px; text-align:center;">W</th>
                    <th style="padding:8px; text-align:center;">L</th>
                    <th style="padding:8px; text-align:center;">Pts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group['standings'] as $row)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding:8px 0; font-weight:600;">{{ $row['participant'] }}</td>
                    <td style="padding:8px; text-align:center;">{{ $row['wins'] }}</td>
                    <td style="padding:8px; text-align:center;">{{ $row['losses'] }}</td>
                    <td style="padding:8px; text-align:center; font-weight:700; color:var(--accent);">{{ $row['points'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Matches --}}
        <div>
            <h4 style="font-size:12px; color:var(--muted); text-transform:uppercase; margin-bottom:12px; border-bottom:1px solid var(--border); padding-bottom:6px;">Daftar Pertandingan</h4>
            @foreach($group['matches'] as $match)
                <div style="margin-bottom: 12px;">
                    @include('host._bracket_match', [
                        'match' => $match, 
                        'competition' => $competition, 
                        'isVisualBye' => false, 
                        'isSeedingMode' => false,
                        'isPublic' => $isPublic ?? false
                    ])
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
