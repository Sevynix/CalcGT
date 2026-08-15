@extends('layouts.host')
@section('title', 'FFA — ' . $competition->name)

@push('styles')
<style>
.ffa-page { padding: 24px 0 80px; }
.ffa-header { margin-bottom: 28px; }
.ffa-title { font-size: 22px; font-weight: 800; }
.ffa-sub { color: var(--muted); font-size: 13px; margin-top: 6px; }

.ffa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    margin: 20px 0;
}

.ffa-card {
    background: var(--card);
    border: 2px solid var(--border);
    border-radius: 14px;
    padding: 18px;
    text-align: center;
    transition: all .2s;
    cursor: pointer;
    user-select: none;
    position: relative;
    overflow: hidden;
}
.ffa-card::before {
    content:'';
    position:absolute; inset:0;
    background:linear-gradient(135deg, rgba(46,213,115,.1), rgba(46,213,115,.05));
    opacity:0; transition:.2s;
}
.ffa-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.3); }
.ffa-card:hover::before { opacity:.5; }

.ffa-card.passed {
    border-color: var(--success);
    background: rgba(46,213,115,.06);
}
.ffa-card.passed::before { opacity:1; }

.ffa-name {
    font-size: 14px; font-weight: 700; color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.ffa-status {
    margin-top: 8px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .4px; text-transform: uppercase;
    color: var(--muted);
}
.ffa-card.passed .ffa-status { color: var(--success); }

.ffa-check {
    position: absolute; top: 10px; right: 10px;
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
    background: var(--bg);
    font-size: 11px;
}
.ffa-card.passed .ffa-check {
    border-color: var(--success);
    background: var(--success);
    color: #fff;
}

.ffa-stats {
    background: var(--card); border: 1px solid var(--border);
    border-radius: 12px; padding: 16px 20px;
    display: flex; gap: 32px; align-items: center;
    margin-bottom: 24px;
}
.stat-num { font-size: 24px; font-weight: 800; color: var(--accent); }
.stat-label { font-size: 12px; color: var(--muted); }
.stat-num.passed { color: var(--success); }

.finalize-btn {
    padding: 12px 28px;
    background: linear-gradient(135deg, var(--success), #27ae60);
    color: #fff; border: none; border-radius: 10px;
    font-size: 14px; font-weight: 700; cursor: pointer;
    transition: all .2s; display: flex; align-items: center; gap: 8px;
    font-family: 'Inter', sans-serif;
}
.finalize-btn:hover { opacity: .9; transform: translateY(-1px); }

.alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; }
.alert-success { background:rgba(46,213,115,.12); color:var(--success); border:1px solid rgba(46,213,115,.2); }
.btn { display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;border:none;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;font-family:inherit; }
.btn-ghost { background:transparent;color:var(--muted);border:1px solid var(--border); }
.btn-ghost:hover { background:var(--border);color:var(--text); }
</style>
@endpush

@section('content')
<div class="ffa-page">
    <div class="ffa-header">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 class="ffa-title">🎲 Free For All — {{ $competition->name }}</h1>
                <p class="ffa-sub">Klik kartu peserta untuk menandai mereka sebagai <strong>lolos</strong>. Host bebas memilih berapa saja yang lolos.</p>
            </div>
            <a href="{{ route('host.bracket', $competition) }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="ffa-stats">
        <div>
            <div class="stat-num">{{ $results->count() }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
        <div>
            <div class="stat-num passed" id="passedCount">{{ $results->where('passed', true)->count() }}</div>
            <div class="stat-label">Ditandai Lolos</div>
        </div>
        <div style="margin-left:auto;">
            @if($phase->status !== 'finished')
                <form method="POST" action="{{ route('host.ffa.finalize', $competition) }}" x-data
                  @submit.prevent="$store.modal.show('Finalisasi FFA? Peserta yang lolos akan maju ke fase berikutnya.', () => $el.submit(), 'Konfirmasi', 'Ya, Finalisasi', 'primary')">
                @csrf
                <button class="finalize-btn">✅ Finalisasi & Lanjut</button>
            </form>
            @else
                <span style="color:var(--muted);font-size:13px;">FFA sudah difinalisasi.</span>
            @endif
        </div>
    </div>

    {{-- Participant grid --}}
    <div class="ffa-grid" id="ffaGrid">
        @foreach($results as $result)
        <div class="ffa-card {{ $result->passed ? 'passed' : '' }}"
             id="card-{{ $result->id }}"
             @if($phase->status !== 'finished')
             onclick="togglePassed({{ $result->id }})"
             @endif
        >
            <div class="ffa-check">{{ $result->passed ? '✓' : '' }}</div>
            <div class="ffa-name">{{ $result->participant_name }}</div>
            <div class="ffa-status">{{ $result->passed ? '✓ LOLOS' : '—' }}</div>
        </div>
        @endforeach
    </div>
</div>

<script>
let passedCount = {{ $results->where('passed', true)->count() }};

function togglePassed(resultId) {
    fetch(`{{ url('/host/ffa-result') }}/${resultId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;

        const card = document.getElementById(`card-${resultId}`);
        const check = card.querySelector('.ffa-check');
        const status = card.querySelector('.ffa-status');

        if (data.passed) {
            card.classList.add('passed');
            check.textContent = '✓';
            status.textContent = '✓ LOLOS';
            passedCount++;
        } else {
            card.classList.remove('passed');
            check.textContent = '';
            status.textContent = '—';
            passedCount--;
        }

        document.getElementById('passedCount').textContent = passedCount;
    });
}
</script>
@endsection
